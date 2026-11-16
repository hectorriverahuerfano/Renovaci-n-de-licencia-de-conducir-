@extends('layouts.app')

@section('title', 'Paso 3: Confirmación de Datos y Pago de Aranceles — Renovación GOES')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Stepper de 5 Fases -->
    <x-stepper :currentStep="3" />

    <!-- Ficha de Datos Verificados del Ciudadano -->
    <section class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#0F2C59] font-heading">
                    Paso 3: Confirmación de Datos y Liquidación de Aranceles
                </h1>
                <p class="text-xs sm:text-sm text-slate-600">
                    Cotejo verificado con éxito ante el Registro Nacional de las Personas Naturales (RNPN).
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Solvente ante el VMT</span>
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs sm:text-sm">
            <div>
                <span class="text-slate-500 block text-xs">Nombre Completo:</span>
                <strong class="text-slate-900 block font-semibold text-sm">{{ $solicitud->ciudadano->nombreCompleto() }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block text-xs">{{ $solicitud->ciudadano->tipo_documento->label() }}:</span>
                <strong class="text-slate-900 block font-mono text-sm">{{ $solicitud->ciudadano->numero_documento }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block text-xs">Licencia Actual:</span>
                <strong class="text-slate-900 block font-mono text-sm">{{ $solicitud->licencia?->numero_licencia ?? 'Pendiente' }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block text-xs">Clase a Renovar:</span>
                <span class="inline-block mt-0.5 px-2 py-0.5 rounded font-bold bg-blue-100 text-blue-900 text-xs">
                    {{ $solicitud->licencia?->clase?->shortName() ?? $solicitud->clase_tramite }}
                </span>
            </div>
        </div>
    </section>

    <!-- Desglose Inmutable y Transparente de Aranceles Oficiales -->
    <section class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs space-y-4">
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-[#0F2C59]">
                    Desglose Oficial de Aranceles e Impuestos
                </h2>
                <p class="text-xs text-slate-500">
                    Tarifas fijadas por la Ley de Tránsito Terrestre y reguladas por el Ministerio de Hacienda.
                </p>
            </div>
            <span class="text-xs font-semibold px-2 py-1 bg-slate-100 text-slate-700 rounded">
                Cero Comisiones Ocultas
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px]">
                        <th class="py-2.5">Concepto Normativo</th>
                        <th class="py-2.5">Institución Beneficiaria</th>
                        <th class="py-2.5 text-right">Monto (USD)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr>
                        <td class="py-3">
                            <strong class="text-slate-900 block">Derecho de Renovación de Licencia de Conducir</strong>
                            <span class="text-xs text-slate-500">Vigencia por {{ $solicitud->licencia?->clase?->vigenciaAnios() ?? 5 }} años según Decreto Legislativo</span>
                        </td>
                        <td class="py-3 text-slate-600">Dirección General de Tesorería (DGT)</td>
                        <td class="py-3 text-right font-mono font-semibold">US$ {{ number_format($solicitud->monto_tramite, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-3">
                            <strong class="text-slate-900 block">Evaluación de Agudeza Visual en Sede</strong>
                            <span class="text-xs text-slate-500">Examen optométrico obligatorio de 5 minutos en SERTRACEN</span>
                        </td>
                        <td class="py-3 text-slate-600">SERTRACEN El Salvador</td>
                        <td class="py-3 text-right font-mono font-semibold">US$ {{ number_format($solicitud->monto_examen_visual, 2) }}</td>
                    </tr>
                    @if($solicitud->monto_mora > 0)
                        <tr class="bg-amber-50/70 text-amber-900">
                            <td class="py-3 pl-2">
                                <strong class="block font-bold">Multa por Renovación Extemporánea (Mora Tardía)</strong>
                                <span class="text-xs text-amber-700">Aplica por renovar después del mes oficial de vencimiento</span>
                            </td>
                            <td class="py-3 text-amber-800">Viceministerio de Transporte (VMT)</td>
                            <td class="py-3 text-right font-mono font-bold text-amber-950 pr-2">US$ {{ number_format($solicitud->monto_mora, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="text-slate-400">
                        <td class="py-2.5 text-xs">Comisión por procesamiento digital o uso de plataforma</td>
                        <td class="py-2.5 text-xs">Gobierno de El Salvador</td>
                        <td class="py-2.5 text-right font-mono text-emerald-600 font-bold">GRATIS ($0.00)</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-900 font-bold text-slate-900 text-sm sm:text-base">
                        <td class="py-3.5" colspan="2">TOTAL EXIGIBLE A CANCELAR:</td>
                        <td class="py-3.5 text-right font-mono text-lg text-blue-900 font-extrabold">
                            US$ {{ number_format($solicitud->monto_total, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>

    <!-- Modalidades de Pago y Liquidación -->
    <section class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs space-y-5">
        <h2 class="text-lg font-bold text-[#0F2C59] border-b border-slate-100 pb-3">
            Seleccione el Método de Pago
        </h2>

        <!-- Selector de Pestañas de Pago -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="metodos-pago-tabs">
            <button type="button"
                    onclick="window.seleccionarMetodo('DIGITAL')"
                    id="btn-tab-digital"
                    class="p-4 rounded-xl border-2 text-left transition-all border-blue-600 bg-blue-50/40 ring-2 ring-blue-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-sm text-[#0F2C59]">💳 Pago en Línea Inmediato</span>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Recomendado</span>
                </div>
                <p class="text-xs text-slate-600">
                    Acepta Tarjeta de Débito/Crédito (Visa, Mastercard), Chivo Wallet o Transferencia 365. Acreditación inmediata.
                </p>
            </button>

            <button type="button"
                    onclick="window.seleccionarMetodo('NPE')"
                    id="btn-tab-npe"
                    class="p-4 rounded-xl border-2 text-left transition-all border-slate-200 bg-white hover:bg-slate-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-sm text-slate-800">📄 Mandamiento NPE en Efectivo</span>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">En ventanilla</span>
                </div>
                <p class="text-xs text-slate-600">
                    Genera un código NPE de 30 dígitos para pagar en efectivo en cualquier sucursal bancaria autorizada o caja SERTRACEN.
                </p>
            </button>
        </div>

        <!-- Formulario Opción A: Pago en Línea Digital -->
        <div id="panel-pago-digital" class="space-y-4 pt-2">
            <form action="{{ route('tramite.pago.post') }}" method="POST" id="form-pago-digital" class="space-y-4">
                @csrf
                <input type="hidden" name="solicitud_id" value="{{ $solicitud->id }}">
                <input type="hidden" name="metodo_pago" value="TARJETA">

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 uppercase">Pasarela de Pagos Segura GOES (PCI-DSS)</span>
                        <div class="flex items-center gap-2 text-slate-400">
                            <span class="text-xs font-semibold text-slate-600">Visa / Mastercard / Chivo</span>
                        </div>
                    </div>

                    <div>
                        <label for="titular_tarjeta" class="block text-xs font-semibold text-slate-700 mb-1">Nombre en la Tarjeta *</label>
                        <input type="text" id="titular_tarjeta" name="titular_tarjeta" value="{{ old('titular_tarjeta', $solicitud->ciudadano->nombreCompleto()) }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="numero_tarjeta" class="block text-xs font-semibold text-slate-700 mb-1">Número de Tarjeta *</label>
                        <input type="text" id="numero_tarjeta" name="numero_tarjeta" value="{{ old('numero_tarjeta', '4000 1234 5678 9010') }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 font-mono focus:ring-2 focus:ring-blue-500" placeholder="0000 0000 0000 0000">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="expiracion" class="block text-xs font-semibold text-slate-700 mb-1">Vencimiento (MM/AA) *</label>
                            <input type="text" id="expiracion" name="expiracion" value="{{ old('expiracion', '12/28') }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 font-mono focus:ring-2 focus:ring-blue-500" placeholder="12/28">
                        </div>
                        <div>
                            <label for="cvv" class="block text-xs font-semibold text-slate-700 mb-1">Código de Seguridad (CVV) *</label>
                            <input type="password" id="cvv" name="cvv" value="{{ old('cvv', '789') }}" required maxlength="4" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 font-mono focus:ring-2 focus:ring-blue-500" placeholder="•••">
                        </div>
                    </div>
                </div>

                <!-- Botón de Confirmación Modal Propio -->
                <button type="button"
                        onclick="window.openGoesModal('confirmacion-pago-modal')"
                        class="w-full py-3.5 px-4 rounded-xl text-center font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-blue-300 text-sm sm:text-base flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Pagar y Continuar a Cita (US$ {{ number_format($solicitud->monto_total, 2) }})</span>
                </button>
            </form>
        </div>

        <!-- Formulario Opción B: Mandamiento NPE en Efectivo -->
        <div id="panel-pago-npe" class="space-y-4 pt-2 hidden">
            <div class="bg-amber-50/80 border border-amber-200 rounded-xl p-4 text-xs sm:text-sm text-slate-800 space-y-2">
                <div class="flex items-center gap-2 font-bold text-amber-900">
                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span>Instrucciones para Liquidación en Efectivo con NPE</span>
                </div>
                <p>
                    Al presionar "Generar NPE y Continuar", se emitirá su mandamiento de pago oficial con código de barras y vigencia de 8 días hábiles. Podrá cancelar en bancos autorizados o directamente en SERTRACEN antes de pasar a su examen visual.
                </p>
            </div>

            <form action="{{ route('tramite.pago.post') }}" method="POST">
                @csrf
                <input type="hidden" name="solicitud_id" value="{{ $solicitud->id }}">
                <input type="hidden" name="metodo_pago" value="MANDAMIENTO_NPE">

                <button type="submit"
                        class="w-full py-3.5 px-4 rounded-xl text-center font-bold text-slate-900 bg-amber-400 hover:bg-amber-500 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-amber-200 text-sm sm:text-base flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Generar Mandamiento NPE y Continuar a Agendar Cita</span>
                </button>
            </form>
        </div>
    </section>

    <!-- Modal de Confirmación de Transacción Financiera Propio (Sin Alertas Nativas) -->
    <x-modal name="confirmacion-pago-modal" title="Confirmación de Pago Oficial GOES">
        <div class="space-y-4">
            <div class="bg-blue-50 border-l-4 border-blue-600 p-3.5 rounded-r-lg text-xs sm:text-sm text-slate-700">
                Está a punto de autorizar el débito por aranceles oficiales gubernamentales:
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-xs sm:text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Contribuyente:</span>
                    <strong class="text-slate-900">{{ $solicitud->ciudadano->nombreCompleto() }}</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Concepto:</span>
                    <span>Renovación de Licencia ({{ $solicitud->clase_tramite }})</span>
                </div>
                <div class="flex justify-between border-t border-slate-200 pt-2 font-bold text-base">
                    <span>Monto Total a Debitar:</span>
                    <span class="text-blue-900 font-mono">US$ {{ number_format($solicitud->monto_total, 2) }}</span>
                </div>
            </div>

            <p class="text-xs text-slate-500">
                Esta transacción cuenta con firma electrónica certificada y cumple con la normativa de seguridad bancaria nacional.
            </p>
        </div>

        <x-slot:footer>
            <button type="button" onclick="window.closeGoesModal('confirmacion-pago-modal')" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg">
                Cancelar
            </button>
            <button type="button" onclick="document.getElementById('form-pago-digital').submit()" class="px-5 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-sm">
                Confirmar y Pagar US$ {{ number_format($solicitud->monto_total, 2) }}
            </button>
        </x-slot:footer>
    </x-modal>
</div>

@push('scripts')
<script>
    window.seleccionarMetodo = function(metodo) {
        const btnDigital = document.getElementById('btn-tab-digital');
        const btnNpe = document.getElementById('btn-tab-npe');
        const panelDigital = document.getElementById('panel-pago-digital');
        const panelNpe = document.getElementById('panel-pago-npe');

        if (metodo === 'DIGITAL') {
            btnDigital.className = 'p-4 rounded-xl border-2 text-left transition-all border-blue-600 bg-blue-50/40 ring-2 ring-blue-100';
            btnNpe.className = 'p-4 rounded-xl border-2 text-left transition-all border-slate-200 bg-white hover:bg-slate-50';
            panelDigital.classList.remove('hidden');
            panelNpe.classList.add('hidden');
        } else {
            btnNpe.className = 'p-4 rounded-xl border-2 text-left transition-all border-blue-600 bg-blue-50/40 ring-2 ring-blue-100';
            btnDigital.className = 'p-4 rounded-xl border-2 text-left transition-all border-slate-200 bg-white hover:bg-slate-50';
            panelNpe.classList.remove('hidden');
            panelDigital.classList.add('hidden');
        }
    };
</script>
@endpush
@endsection
