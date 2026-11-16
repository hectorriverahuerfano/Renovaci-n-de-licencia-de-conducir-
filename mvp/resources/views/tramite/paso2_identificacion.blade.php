@extends('layouts.app')

@section('title', 'Paso 2: Identificación Ciudadana y Solvencia VMT — Renovación de Licencia')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Stepper de 5 Fases -->
    <x-stepper :currentStep="2" />

    <!-- Encabezado de Sección -->
    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#0F2C59] font-heading">
                    Identificación Ciudadana y Verificación de Solvencia VMT
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                    Ingrese los datos de su documento oficial para cotejar su identidad con el RNPN y consultar su historial de tránsito.
                </p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-200 hidden sm:inline-block">
                Interconexión Segura TLS 1.3
            </span>
        </div>

        <!-- 1. Alerta de Esquela Pagada con Éxito (Transición Verde de Recuperación) -->
        @if(session('esquela_pagada_exito'))
            <div role="status" class="rounded-xl border-2 border-emerald-500 bg-emerald-50 p-5 shadow-xs mb-6">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 text-emerald-600">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-base font-bold text-emerald-900 mb-1">
                            ¡Esquela Solventada Exitosamente en el VMT!
                        </h2>
                        <p class="text-sm text-slate-700 mb-2">
                            {{ session('esquela_pagada_exito')['mensaje'] }} Referencia de comprobante: <strong class="font-mono">{{ session('esquela_pagada_exito')['transaccion'] }}</strong>.
                        </p>
                        <p class="text-xs text-emerald-800">
                            Su estado ahora se encuentra <strong>SOLVENTE</strong> ante el Viceministerio de Transporte. Puede hacer clic en "Continuar al Paso 3" para proceder con su renovación.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- 2. Bloqueo Recuperable: Esquela Pendiente detectada -->
        @if(session('recuperacion_esquela'))
            @php $rec = session('recuperacion_esquela'); @endphp
            <x-alert-recovery
                type="esquela"
                :title="'Infracción de tránsito pendiente de pago detectada en el VMT (' . $rec['cantidad_total'] . ')'"
                :description="'Para continuar con la emisión legal de su licencia de conducir, la Ley de Transporte Terrestre exige solventar cualquier infracción registrada a su nombre. Puede cancelarla en línea de inmediato con acreditación instantánea sin reiniciar su trámite.'"
                :esquelaId="$rec['esquela_id']"
                :referenceNumber="$rec['numero_esquela'] . ' — ' . $rec['infraccion']"
                :amount="$rec['monto']"
            />
        @endif

        <!-- 3. Alerta de Resiliencia: Timeout o Caída de API Simulada -->
        @if(session('error_api'))
            @php $apiErr = session('error_api'); @endphp
            <x-alert-recovery
                type="api_timeout"
                :title="$apiErr['title']"
                :description="$apiErr['message']"
            />
        @endif

        <!-- 4. Alerta de Restricción Normativa / Caso No Elegible -->
        @if(session('bloqueo_legal'))
            @php $legal = session('bloqueo_legal'); @endphp
            <x-alert-recovery
                type="bloqueo_legal"
                :title="'Aviso de Elegibilidad Normativa (' . $legal['code'] . ')'"
                :description="$legal['message'] . ' Referencia: ' . ($legal['legal_reference'] ?? 'Normativa VMT')"
            />
        @endif

        <!-- Formulario Principal de Identificación -->
        <form action="{{ route('tramite.identificacion.post') }}" method="POST" id="form-identificacion" class="space-y-6">
            @csrf

            <!-- Selector de Tipo de Documento -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Tipo de Documento Oficial de Identidad *
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Opción DUI -->
                    <label class="flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all
                        {{ ($tipoDoc ?? 'DUI') === 'DUI' ? 'border-blue-600 bg-blue-50/40 text-blue-900 font-semibold shadow-2xs' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                        <input type="radio" name="tipo_documento" value="DUI" class="text-blue-600 focus:ring-blue-500"
                               {{ ($tipoDoc ?? 'DUI') === 'DUI' ? 'checked' : '' }}
                               onchange="window.location.href='{{ route('tramite.identificacion', ['tipo_doc' => 'DUI']) }}'">
                        <div>
                            <span class="block text-sm">DUI Salvadoreño</span>
                            <span class="block text-[11px] text-slate-500 font-normal">Nacionales (8+1 dígitos)</span>
                        </div>
                    </label>

                    <!-- Opción Residente Extranjero -->
                    <label class="flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all
                        {{ in_array($tipoDoc ?? '', ['RESIDENTE_TEMPORAL', 'RESIDENTE_DEFINITIVO']) ? 'border-blue-600 bg-blue-50/40 text-blue-900 font-semibold shadow-2xs' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                        <input type="radio" name="tipo_documento" value="RESIDENTE_TEMPORAL" class="text-blue-600 focus:ring-blue-500"
                               {{ in_array($tipoDoc ?? '', ['RESIDENTE_TEMPORAL', 'RESIDENTE_DEFINITIVO']) ? 'checked' : '' }}
                               onchange="window.location.href='{{ route('tramite.identificacion', ['tipo_doc' => 'RESIDENTE_TEMPORAL']) }}'">
                        <div>
                            <span class="block text-sm">Carnet de Residente</span>
                            <span class="block text-[11px] text-slate-500 font-normal">Extranjeros con residencia</span>
                        </div>
                    </label>

                    <!-- Opción Diplomático -->
                    <label class="flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all
                        {{ ($tipoDoc ?? '') === 'DIPLOMATICO' ? 'border-blue-600 bg-blue-50/40 text-blue-900 font-semibold shadow-2xs' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                        <input type="radio" name="tipo_documento" value="DIPLOMATICO" class="text-blue-600 focus:ring-blue-500"
                               {{ ($tipoDoc ?? '') === 'DIPLOMATICO' ? 'checked' : '' }}
                               onchange="window.location.href='{{ route('tramite.identificacion', ['tipo_doc' => 'DIPLOMATICO']) }}'">
                        <div>
                            <span class="block text-sm">Carnet Diplomático</span>
                            <span class="block text-[11px] text-slate-500 font-normal">Cuerpo consular acreditado</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Campo Número de Documento con máscara interactiva -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="numero_documento" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        {{ ($tipoDoc ?? 'DUI') === 'DUI' ? 'Número de DUI (con guion) *' : 'Número de Carnet Oficial *' }}
                    </label>
                    <span class="text-xs text-slate-500" id="doc-hint">
                        {{ ($tipoDoc ?? 'DUI') === 'DUI' ? 'Formato oficial: 00000000-0' : 'Ingrese el código alfanumérico tal como figura en su carné' }}
                    </span>
                </div>
                
                <div class="relative rounded-lg shadow-2xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    </div>
                    <input type="text"
                           id="numero_documento"
                           name="numero_documento"
                           value="{{ old('numero_documento', $numDoc ?? '') }}"
                           required
                           class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 text-slate-900 font-mono text-base focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('numero_documento') border-red-500 ring-1 ring-red-500 @enderror"
                           placeholder="{{ ($tipoDoc ?? 'DUI') === 'DUI' ? '02489123-4' : 'RES-TMP-2026-01' }}"
                           autocomplete="off">
                </div>

                @error('numero_documento')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Canales de Notificación para Avisos Oficiales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="correo" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Correo Electrónico (para comprobante oficial)
                    </label>
                    <input type="email"
                           id="correo"
                           name="correo"
                           value="{{ old('correo') }}"
                           class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                           placeholder="ejemplo@correo.com">
                </div>
                <div>
                    <label for="telefono" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Teléfono Móvil (alertas SMS para su cita)
                    </label>
                    <input type="tel"
                           id="telefono"
                           name="telefono"
                           value="{{ old('telefono') }}"
                           class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                           placeholder="7845-1234">
                </div>
            </div>

            <!-- Checkbox de Simulación para Demostración de Falla de Integración (Resiliencia) -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <input type="checkbox"
                           id="simular_falla_api"
                           name="simular_falla_api"
                           value="1"
                           {{ ($simularFalla ?? false) ? 'checked' : '' }}
                           class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-slate-300 rounded cursor-pointer">
                    <label for="simular_falla_api" class="text-xs text-slate-700 cursor-pointer select-none">
                        <strong>Simular caída / timeout de API externa VMT</strong> (demuestra recuperación resiliente sin stack traces)
                    </label>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-rose-100 text-rose-800">
                    QA Demo
                </span>
            </div>

            <!-- Botones de Navegación del Flujo -->
            <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('inicio') }}"
                   class="w-full sm:w-auto px-5 py-2.5 text-center text-sm font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 rounded-xl transition-colors">
                    ← Volver al Paso 1: Requisitos
                </a>

                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 text-center text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-blue-300 flex items-center justify-center gap-2">
                    <span>Consultar y Continuar al Paso 3</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
