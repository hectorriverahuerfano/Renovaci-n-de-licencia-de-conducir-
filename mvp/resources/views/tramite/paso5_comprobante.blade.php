@extends('layouts.app')

@section('title', 'Paso 5: Comprobante Oficial y Pase Digital QR — GOES')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Stepper de 5 Fases (Completado) -->
    <div class="no-print">
        <x-stepper :currentStep="5" />
    </div>

    @if(session('reprogramacion_exitosa'))
        <div role="status" class="no-print p-4 bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg text-emerald-900 text-sm flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('reprogramacion_exitosa') }}</span>
        </div>
    @endif

    <!-- Tarjeta Principal del Pase Digital y Comprobante Oficial -->
    <article class="printable-card bg-white rounded-2xl border-2 border-slate-300 shadow-lg overflow-hidden">
        <!-- Cabecera Oficial del Comprobante -->
        <div class="bg-[#0F2C59] text-white p-6 sm:p-8 border-b-4 border-amber-400">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest text-blue-200 font-bold block mb-1">
                        República de El Salvador • VMT • SERTRACEN
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading">
                        Pase Oficial de Fila Express — Renovación
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1">
                        Constancia electrónica con código de verificación gubernamental.
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur-xs p-3.5 rounded-xl border border-white/20 text-center sm:text-right shrink-0">
                    <span class="text-[10px] uppercase font-bold text-slate-300 block">Código Único de Trámite:</span>
                    <span class="text-lg sm:text-xl font-mono font-extrabold text-amber-300 tracking-wider">
                        {{ $solicitud->codigo_solicitud }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <!-- Bloque Destacado con Código QR y Datos de Cita -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center bg-slate-50 p-6 rounded-2xl border border-slate-200">
                <!-- Columna QR Vectorial Puro -->
                <div class="md:col-span-5 flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    <div class="p-2 border-2 border-[#0F2C59] rounded-xl bg-white">
                        {!! $qrSvg !!}
                    </div>
                    <span class="mt-2 text-[11px] font-bold text-slate-700 tracking-wide uppercase text-center">
                        Escanear en Tótem de Entrada
                    </span>
                    <span class="text-[10px] text-slate-500 text-center">
                        Fila Express SERTRACEN
                    </span>
                </div>

                <!-- Columna Datos de Cita y Sucursal -->
                <div class="md:col-span-7 space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Cita Confirmada y Habilitada</span>
                    </div>

                    <h2 class="text-lg font-bold text-[#0F2C59] font-heading">
                        {{ $solicitud->cita?->sucursal?->nombre ?? 'Sucursal SERTRACEN' }}
                    </h2>
                    <p class="text-xs text-slate-600">
                        {{ $solicitud->cita?->sucursal?->direccion }}
                    </p>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="bg-white p-3 rounded-lg border border-slate-200">
                            <span class="text-[11px] text-slate-500 block uppercase font-bold">Fecha del Turno:</span>
                            <span class="text-sm font-bold text-slate-900">
                                {{ $solicitud->cita?->fecha_cita?->format('d/m/Y') ?? 'Por confirmar' }}
                            </span>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-slate-200">
                            <span class="text-[11px] text-slate-500 block uppercase font-bold">Hora Programada:</span>
                            <span class="text-sm font-bold text-blue-700 font-mono">
                                {{ $solicitud->cita?->hora_cita ?? '09:00 AM' }}
                            </span>
                        </div>
                    </div>

                    <div class="text-xs text-slate-700 bg-amber-50 border border-amber-200 p-2.5 rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        <span>Garantía de atención: Tiempo en sucursal menor a 15 minutos.</span>
                    </div>
                </div>
            </div>

            <!-- Ficha Técnica Resumen del Trámite -->
            <div class="border-t border-slate-200 pt-5 space-y-3">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Resumen del Contribuyente y Aranceles Acreditados
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-slate-500 block text-xs">Ciudadano:</span>
                        <strong class="text-slate-900">{{ $solicitud->ciudadano->nombreCompleto() }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs">{{ $solicitud->ciudadano->tipo_documento->label() }}:</span>
                        <strong class="text-slate-900 font-mono">{{ $solicitud->ciudadano->numero_documento }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs">Clase Renovada:</span>
                        <strong class="text-blue-900">{{ $solicitud->clase_tramite }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs">Monto Total Pagado:</span>
                        <strong class="text-emerald-700 font-mono text-base font-extrabold">US$ {{ number_format($solicitud->monto_total, 2) }}</strong>
                    </div>
                </div>

                @if($solicitud->pagos->first()?->npe)
                    <div class="mt-3 p-3 bg-slate-100 rounded-lg border border-slate-300 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="text-[11px] font-bold text-slate-600 uppercase block">NPE para Pago Bancario (30 dígitos):</span>
                            <span class="font-mono text-sm font-bold text-slate-900 select-all">{{ $solicitud->pagos->first()->npe }}</span>
                        </div>
                        <span class="text-xs text-slate-500">Vence: {{ $solicitud->pagos->first()->npe_vencimiento?->format('d/m/Y') }}</span>
                    </div>
                @endif
            </div>

            <!-- Timeline Visual de Fases del Trámite -->
            <div class="border-t border-slate-200 pt-5">
                <h3 class="text-sm font-bold text-[#0F2C59] mb-3">
                    Hoja de Ruta y Tiempos Estimados del Trámite
                </h3>
                <x-timeline :events="$timelineEvents" />
            </div>

            <!-- Botones de Acción (Se ocultan en impresión) -->
            <div class="no-print border-t border-slate-200 pt-6 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button"
                            onclick="window.print()"
                            class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Descargar Comprobante PDF / Imprimir
                    </button>

                    <button type="button"
                            onclick="window.openGoesModal('reprogramar-cita-modal')"
                            class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-800 bg-white hover:bg-slate-100 border border-slate-300 transition-colors">
                        <svg class="w-4 h-4 mr-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Reprogramar Cita
                    </button>
                </div>

                <a href="{{ route('seguimiento', ['criterio' => $solicitud->codigo_solicitud]) }}"
                   class="text-sm font-semibold text-blue-700 hover:text-blue-900 underline">
                    Consultar Estado en Portal de Seguimiento →
                </a>
            </div>
        </div>
    </article>

    <!-- Modal Accesible Propio de Reprogramación de Cita (Sin Ventanas Nativas) -->
    <x-modal name="reprogramar-cita-modal" title="Reprogramar Turno en SERTRACEN (Sin Costo)">
        <form action="{{ route('tramite.reprogramar-cita', ['codigo' => $solicitud->codigo_solicitud]) }}" method="POST" class="space-y-4">
            @csrf
            <p class="text-xs text-slate-600">
                Puede cambiar la sede o la fecha de su examen visual hasta con 2 horas de anticipación sin ningún recargo ni penalización.
            </p>

            <div>
                <label for="reprog-sucursal" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nueva Sucursal *</label>
                <select id="reprog-sucursal" name="sucursal_id" required class="w-full text-sm rounded-lg border-slate-300 p-2.5 border bg-white">
                    <option value="1" {{ ($solicitud->cita?->sucursal_id == 1) ? 'selected' : '' }}>SERTRACEN Las Cascadas (Antiguo Cuscatlán)</option>
                    <option value="2" {{ ($solicitud->cita?->sucursal_id == 2) ? 'selected' : '' }}>SERTRACEN Central San Salvador (Autopista Sur)</option>
                    <option value="3" {{ ($solicitud->cita?->sucursal_id == 3) ? 'selected' : '' }}>SERTRACEN Santa Ana (Plaza Kristal)</option>
                    <option value="4" {{ ($solicitud->cita?->sucursal_id == 4) ? 'selected' : '' }}>SERTRACEN San Miguel (Metrocentro)</option>
                    <option value="5" {{ ($solicitud->cita?->sucursal_id == 5) ? 'selected' : '' }}>Ventanilla Especial Prioritaria (Inclusiva)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="reprog-fecha" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nueva Fecha *</label>
                    <input type="date" id="reprog-fecha" name="fecha_cita" min="{{ date('Y-m-d') }}" value="{{ $solicitud->cita?->fecha_cita?->addDays(2)?->format('Y-m-d') ?? date('Y-m-d') }}" required class="w-full text-sm rounded-lg border-slate-300 p-2 border">
                </div>
                <div>
                    <label for="reprog-hora" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nuevo Turno Horario *</label>
                    <select id="reprog-hora" name="hora_cita" required class="w-full text-sm rounded-lg border-slate-300 p-2 border bg-white">
                        <option value="09:15 AM">09:15 AM</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="11:30 AM">11:30 AM</option>
                        <option value="02:00 PM">02:00 PM</option>
                        <option value="03:30 PM">03:30 PM</option>
                    </select>
                </div>
            </div>

            <div class="pt-3 flex justify-end gap-2.5">
                <button type="button" onclick="window.closeGoesModal('reprogramar-cita-modal')" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-sm">
                    Guardar Nueva Cita
                </button>
            </div>
        </form>
    </x-modal>
</div>
@endsection
