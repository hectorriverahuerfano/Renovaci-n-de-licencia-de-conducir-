<?php

namespace App\Http\Controllers;

use App\Enums\ClaseLicencia;
use App\Enums\SolicitudEstado;
use App\Enums\TipoDocumento;
use App\Exceptions\ExternalServiceTimeoutException;
use App\Http\Requests\BookAppointmentRequest;
use App\Http\Requests\ConfirmApplicationRequest;
use App\Http\Requests\IdentifyCitizenRequest;
use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Cita;
use App\Models\CiudadanoDemo;
use App\Models\Esquela;
use App\Models\Licencia;
use App\Models\Solicitud;
use App\Models\Sucursal;
use App\Services\AppointmentService;
use App\Services\EligibilityService;
use App\Services\FineQueryService;
use App\Services\PaymentService;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RenewalController extends Controller
{
    public function __construct(
        protected EligibilityService $eligibilityService,
        protected FineQueryService $fineQueryService,
        protected PaymentService $paymentService,
        protected AppointmentService $appointmentService,
        protected QrCodeService $qrCodeService
    ) {}

    /**
     * Paso 1: Portal de Inicio, Requisitos y Elegibilidad.
     */
    public function index(Request $request): View
    {
        $clases = ClaseLicencia::cases();
        $claseSeleccionada = $request->query('clase', 'PARTICULAR');

        // Casos rápidos de prueba para el evaluador (Tester Sandbox)
        $casosPrueba = [
            [
                'id' => 'caso1',
                'badge' => 'Flujo Feliz',
                'badge_color' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'titulo' => 'Salvadoreño al día (Carlos Monterrosa)',
                'dui' => '02489123-4',
                'tipo' => 'DUI',
                'descripcion' => 'Particular, vigente en mes de vencimiento, 0 esquelas. Arancel estándar US$ 56.21.',
            ],
            [
                'id' => 'caso2',
                'badge' => 'Bloqueo Recuperable',
                'badge_color' => 'bg-amber-100 text-amber-800 border-amber-300',
                'titulo' => 'Esquela Pendiente + Vencida (Ana Gómez)',
                'dui' => '05123456-7',
                'tipo' => 'DUI',
                'descripcion' => '1 esquela pendiente (US$ 34.29) + multa tardía (US$ 5.71). Permite pagar y revalidar en línea.',
            ],
            [
                'id' => 'caso3',
                'badge' => 'Resiliencia / Falla API',
                'badge_color' => 'bg-rose-100 text-rose-800 border-rose-300',
                'titulo' => 'Simulación de Caída de API VMT (Roberto Morales)',
                'dui' => '09876543-2',
                'tipo' => 'DUI',
                'descripcion' => 'Simula desconexión de API externa con recuperación asistida sin stack trace.',
            ],
            [
                'id' => 'caso4',
                'badge' => 'Elegibilidad / Reglas',
                'badge_color' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                'titulo' => 'Casos Especiales: Extranjeros & Juveniles',
                'dui' => 'RES-TMP-2026-01',
                'tipo' => 'RESIDENTE_TEMPORAL',
                'descripcion' => 'Carnet temporal con < 3 meses (bloqueo legal) o Canje Juvenil a Adulto (Diego Navas: 07654321-0).',
            ],
        ];

        return view('tramite.paso1_inicio', compact('clases', 'claseSeleccionada', 'casosPrueba'));
    }

    /**
     * Paso 2: Pantalla de Identificación Ciudadana y Consulta de Multas.
     */
    public function showIdentificacion(Request $request): View
    {
        $tipoDoc = $request->query('tipo_doc', 'DUI');
        $numDoc = $request->query('num_doc', '');
        $simularFalla = $request->boolean('simular_falla', false);

        // Si hay una sesión previa o parámetros de recuperación
        $recuperacion = session('recuperacion_esquela');
        $errorApi = session('error_api');
        $bloqueoLegal = session('bloqueo_legal');

        return view('tramite.paso2_identificacion', compact('tipoDoc', 'numDoc', 'simularFalla', 'recuperacion', 'errorApi', 'bloqueoLegal'));
    }

    /**
     * Procesa la identificación, consulta solvencia de multas y evalúa elegibilidad.
     */
    public function postIdentificacion(IdentifyCitizenRequest $request): RedirectResponse|View
    {
        $tipoDoc = $request->validated('tipo_documento');
        $numDoc = trim($request->validated('numero_documento'));
        $simularFalla = $request->boolean('simular_falla_api');

        // Buscar ciudadano en base de datos demo
        $ciudadano = CiudadanoDemo::where('numero_documento', $numDoc)->first();

        if (! $ciudadano) {
            return back()->withInput()->withErrors([
                'numero_documento' => 'No se encontró un registro oficial con el número de documento proporcionado. Verifique e intente nuevamente o solicite Atención Asistida.',
            ]);
        }

        // Obtener la licencia principal del ciudadano
        $licencia = $ciudadano->licencias()->latest()->first();

        // 1. Evaluar elegibilidad legal
        $evaluacion = $this->eligibilityService->checkEligibility($ciudadano, $licencia);
        if (! $evaluacion['eligible']) {
            return back()->withInput()->with('bloqueo_legal', [
                'code' => $evaluacion['code'],
                'message' => $evaluacion['message'],
                'legal_reference' => $evaluacion['legal_reference'],
            ]);
        }

        // 2. Consultar Solvencia de Multas ante el VMT (con resiliencia y timeout controlado)
        try {
            $solvencia = $this->fineQueryService->checkSolvency($ciudadano, $simularFalla);
        } catch (ExternalServiceTimeoutException $e) {
            return back()->withInput()->with('error_api', [
                'title' => 'Interrupción temporal del Sistema de Consultas del VMT',
                'message' => 'No pudimos verificar su solvencia de tránsito debido a una alta demanda o mantenimiento momentáneo en los servidores del Viceministerio de Transporte. Sus datos se encuentran seguros.',
                'can_retry' => true,
                'can_assist' => true,
            ]);
        }

        // 3. Si tiene multas pendientes: Bloqueo recuperable
        if (! $solvencia['solvente']) {
            $primeraEsquela = $solvencia['esquelas']->first();
            return back()->withInput()->with('recuperacion_esquela', [
                'esquela_id' => $primeraEsquela->id,
                'numero_esquela' => $primeraEsquela->numero_esquela,
                'infraccion' => $primeraEsquela->infraccion_codigo . ' — ' . $primeraEsquela->descripcion,
                'monto' => $primeraEsquela->monto,
                'departamento' => $primeraEsquela->departamento,
                'cantidad_total' => $solvencia['cantidad_esquelas'],
                'monto_total' => $solvencia['total_monto'],
            ]);
        }

        // 4. Si es solvente y elegible: Crear o recuperar la solicitud
        $fees = $this->paymentService->calculateFees($licencia);

        $solicitud = Solicitud::updateOrCreate(
            [
                'ciudadano_id' => $ciudadano->id,
                'estado' => SolicitudEstado::INICIADA,
            ],
            [
                'codigo_solicitud' => 'GOES-RN-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'licencia_id' => $licencia->id,
                'clase_tramite' => $licencia->clase->value,
                'monto_tramite' => $fees['tramite'],
                'monto_examen_visual' => $fees['examen_visual'],
                'monto_mora' => $fees['mora'],
                'monto_total' => $fees['total'],
                'correo_contacto' => $request->validated('correo') ?? $ciudadano->correo,
                'telefono_contacto' => $request->validated('telefono') ?? $ciudadano->telefono,
                'paso_actual' => 3,
            ]
        );

        session(['solicitud_id' => $solicitud->id]);

        return redirect()->route('tramite.datos-y-pago');
    }

    /**
     * Acción de recuperación: Simular pago de esquela en línea.
     */
    public function pagarEsquela(Request $request): RedirectResponse
    {
        $esquelaId = $request->validate([
            'esquela_id' => ['required', 'integer', 'exists:esquelas,id'],
        ])['esquela_id'];

        $esquela = $this->fineQueryService->payFine($esquelaId);

        return back()->withInput()->with('esquela_pagada_exito', [
            'numero_esquela' => $esquela->numero_esquela,
            'transaccion' => $esquela->transaccion_pago_ref,
            'mensaje' => "La esquela {$esquela->numero_esquela} ha sido solventada con éxito en el sistema del VMT.",
        ]);
    }

    /**
     * Paso 3: Confirmación de Datos Personales y Desglose de Pago.
     */
    public function showDatosYPago(Request $request): View|RedirectResponse
    {
        $solicitudId = session('solicitud_id') ?? $request->query('solicitud_id');

        if (! $solicitudId) {
            return redirect()->route('tramite.identificacion')->with('warning', 'Inicie su trámite ingresando su documento de identidad.');
        }

        $solicitud = Solicitud::with(['ciudadano', 'licencia'])->findOrFail($solicitudId);

        return view('tramite.paso3_pago', compact('solicitud'));
    }

    /**
     * Confirma los datos del ciudadano antes de pagar.
     */
    public function postConfirmarDatos(ConfirmApplicationRequest $request): RedirectResponse
    {
        $solicitud = Solicitud::findOrFail($request->validated('solicitud_id'));

        $solicitud->update([
            'correo_contacto' => $request->validated('correo_contacto'),
            'telefono_contacto' => $request->validated('telefono_contacto'),
            'estado' => SolicitudEstado::DATOS_CONFIRMADOS,
        ]);

        return back()->with('datos_confirmados_exito', 'Datos de contacto y declaración jurada confirmados correctamente.');
    }

    /**
     * Procesa la transacción de pago (digital o mandamiento NPE).
     */
    public function postPago(ProcessPaymentRequest $request): RedirectResponse
    {
        $solicitud = Solicitud::findOrFail($request->validated('solicitud_id'));
        $metodo = $request->validated('metodo_pago');

        if ($metodo === 'MANDAMIENTO_NPE') {
            $pago = $this->paymentService->generateNpePayment($solicitud);
            session(['pago_id' => $pago->id]);
            return redirect()->route('tramite.cita')->with('info', "Mandamiento NPE generado con éxito. Su código de barra y NPE oficial quedarán vinculados a su cita.");
        }

        // Pago digital directo
        $pago = $this->paymentService->processDigitalPayment($solicitud, $metodo, [
            'titular' => $request->input('titular_tarjeta', 'CIUDADANO GOES'),
            'ultimos_cuatro' => substr($request->input('numero_tarjeta', '4000123456784321'), -4),
        ]);

        session(['pago_id' => $pago->id]);

        return redirect()->route('tramite.cita')->with('success', 'Aranceles acreditados con éxito ante el Ministerio de Hacienda y SERTRACEN.');
    }

    /**
     * Paso 4: Agendamiento de Cita para Examen Visual y Revisión de Originales.
     */
    public function showCita(Request $request): View|RedirectResponse
    {
        $solicitudId = session('solicitud_id') ?? $request->query('solicitud_id');

        if (! $solicitudId) {
            return redirect()->route('tramite.identificacion');
        }

        $solicitud = Solicitud::with(['ciudadano', 'licencia', 'pagos'])->findOrFail($solicitudId);
        $sucursales = $this->appointmentService->getBranches();

        $sucursalSeleccionada = $sucursales->firstWhere('codigo', 'CASCADAS') ?? $sucursales->first();
        $fechaSugerida = Carbon::now()->addDays(2)->format('Y-m-d');
        $slots = $this->appointmentService->getAvailableSlots($sucursalSeleccionada, $fechaSugerida);

        return view('tramite.paso4_cita', compact('solicitud', 'sucursales', 'sucursalSeleccionada', 'fechaSugerida', 'slots'));
    }

    /**
     * Confirma la cita y finaliza el trámite generando el pase digital.
     */
    public function postCita(BookAppointmentRequest $request): RedirectResponse
    {
        $solicitud = Solicitud::findOrFail($request->validated('solicitud_id'));

        $this->appointmentService->bookAppointment(
            $solicitud,
            (int) $request->validated('sucursal_id'),
            $request->validated('fecha_cita'),
            $request->validated('hora_cita'),
            $request->validated('tipo_atencion'),
            $request->boolean('checklist_lentes')
        );

        return redirect()->route('tramite.comprobante', ['codigo' => $solicitud->codigo_solicitud]);
    }

    /**
     * Paso 5: Comprobante Oficial, Pase Fila Express, QR y Seguimiento.
     */
    public function showComprobante(string $codigo): View
    {
        $solicitud = Solicitud::with(['ciudadano', 'licencia', 'cita.sucursal', 'pagos'])
            ->where('codigo_solicitud', $codigo)
            ->firstOrFail();

        // Generar código QR SVG vectorial nativo
        $qrData = route('tramite.comprobante', ['codigo' => $solicitud->codigo_solicitud]);
        $qrSvg = $this->qrCodeService->generateSvg($qrData, 190);

        // Timeline de estados para el componente <x-timeline>
        $timelineEvents = [
            [
                'title' => 'Inicio y Verificación en Línea',
                'description' => 'Elegibilidad jurídica y solvencia de multas verificada ante VMT y RNPN.',
                'date' => $solicitud->created_at->format('d/m/Y h:i A'),
                'completed' => true,
                'current' => false,
            ],
            [
                'title' => 'Pago Oficial de Aranceles',
                'description' => "Liquidación de US$ {$solicitud->monto_total} acreditada al Ministerio de Hacienda.",
                'date' => $solicitud->pagos->first()?->created_at?->format('d/m/Y h:i A') ?? 'En proceso',
                'completed' => true,
                'current' => false,
            ],
            [
                'title' => 'Cita Programada — Fila Express SERTRACEN',
                'description' => "Presentarse con DUI y licencia física en {$solicitud->cita?->sucursal?->nombre} el {$solicitud->cita?->fecha_cita?->format('d/m/Y')} a las {$solicitud->cita?->hora_cita}.",
                'date' => $solicitud->cita?->fecha_cita?->format('d/m/Y') ?? 'Programada',
                'completed' => false,
                'current' => true,
            ],
            [
                'title' => 'Examen Visual Presencial (5 min)',
                'description' => 'Evaluación de agudeza visual obligatoria por Ley de Tránsito en consultorio optométrico.',
                'date' => 'En sucursal',
                'completed' => false,
                'current' => false,
            ],
            [
                'title' => 'Captura Biométrica e Impresión Inmediata (7 min)',
                'description' => 'Toma fotográfica, actualización de firma y entrega de plástico físico de alta durabilidad.',
                'date' => 'En sucursal',
                'completed' => false,
                'current' => false,
            ],
        ];

        return view('tramite.paso5_comprobante', compact('solicitud', 'qrSvg', 'timelineEvents'));
    }

    /**
     * Reprogramación accesible de cita mediante modal propio.
     */
    public function reprogramarCita(Request $request, string $codigo): RedirectResponse
    {
        $solicitud = Solicitud::where('codigo_solicitud', $codigo)->firstOrFail();
        $cita = $solicitud->cita;

        if (! $cita) {
            return back()->with('error', 'No se encontró una cita agendada para reprogramar.');
        }

        $validated = $request->validate([
            'sucursal_id' => ['required', 'integer', 'exists:sucursales,id'],
            'fecha_cita' => ['required', 'date', 'after_or_equal:today'],
            'hora_cita' => ['required', 'string'],
        ]);

        $this->appointmentService->reschedule(
            $cita,
            (int) $validated['sucursal_id'],
            $validated['fecha_cita'],
            $validated['hora_cita']
        );

        return back()->with('reprogramacion_exitosa', 'Su cita ha sido reprogramada con éxito sin costo adicional.');
    }
}
