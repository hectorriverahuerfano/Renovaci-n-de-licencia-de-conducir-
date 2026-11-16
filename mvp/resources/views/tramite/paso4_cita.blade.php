@extends('layouts.app')

@section('title', 'Paso 4: Agendamiento de Cita Examen Visual — SERTRACEN GOES')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Stepper de 5 Fases -->
    <x-stepper :currentStep="4" />

    <!-- Justificación Normativa y Garantía de Fila Express -->
    <section class="bg-gradient-to-r from-blue-900 to-[#0F2C59] rounded-2xl p-6 text-white shadow-md space-y-3">
        <div class="flex items-center gap-2 text-amber-300 text-xs font-bold uppercase tracking-wider">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
            <span>Mandato de Ley — Examen Visual y Biometría Presencial</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold font-heading">
            Agende su Turno en Fila Express SERTRACEN
        </h1>
        <p class="text-slate-200 text-xs sm:text-sm leading-relaxed">
            La legislación de tránsito salvadoreña exige que la prueba optométrica y la toma biométrica se efectúen en consultorio oficial. Con su trámite pagado en línea, accede a la <strong>Fila Express</strong> con un tiempo de permanencia garantizado de <strong>menos de 15 minutos</strong>.
        </p>
    </section>

    <!-- Formulario de Agendamiento -->
    <form action="{{ route('tramite.cita.post') }}" method="POST" id="form-cita" class="space-y-6">
        @csrf
        <input type="hidden" name="solicitud_id" value="{{ $solicitud->id }}">

        <!-- 1. Selección de Sucursal con Aforo en Vivo -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-[#0F2C59] flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-bold">1</span>
                    <span>Seleccione la Sucursal de SERTRACEN</span>
                </h2>
                <span class="text-xs text-slate-500">5 Sedes Oficiales Habilitadas</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                @foreach($sucursales as $suc)
                    <label class="relative flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all hover:border-blue-500
                        {{ $suc->codigo === 'CASCADAS' ? 'border-blue-600 bg-blue-50/30' : 'border-slate-200 bg-white' }}">
                        <input type="radio"
                               name="sucursal_id"
                               value="{{ $suc->id }}"
                               class="mt-1 text-blue-600 focus:ring-blue-500"
                               {{ $suc->codigo === 'CASCADAS' ? 'checked' : '' }}>
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-1 mb-1">
                                <strong class="text-sm text-slate-900 block font-heading">{{ $suc->nombre }}</strong>
                                @if($suc->es_recomendada)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 shrink-0">
                                        ⚡ Menor Espera
                                    </span>
                                @elseif($suc->es_prioritaria)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 shrink-0">
                                        ♿ Inclusiva
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ $suc->direccion }}</p>
                            <div class="mt-2 text-[11px] font-semibold text-slate-600 flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full {{ $suc->es_recomendada ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                <span>Capacidad: {{ $suc->capacidad_por_hora }} atenciones express / hora</span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 2. Selección de Fecha y Turno Horario -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-[#0F2C59] flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-bold">2</span>
                    <span>Fecha y Franja Horaria</span>
                </h2>
                <span class="text-xs text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded">
                    Cupos Disponibles en Vivo
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-1">
                    <label for="fecha_cita" class="block text-xs font-bold text-slate-700 uppercase mb-1">Fecha de la Cita *</label>
                    <input type="date"
                           id="fecha_cita"
                           name="fecha_cita"
                           value="{{ old('fecha_cita', $fechaSugerida) }}"
                           min="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-3 py-2.5 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 font-sans">
                    <span class="text-[11px] text-slate-500 mt-1 block">Atención: Lunes a Sábado</span>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipo de Modalidad de Atención *</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="p-2.5 rounded-lg border border-slate-300 text-xs flex items-center gap-2 cursor-pointer bg-white hover:bg-slate-50">
                            <input type="radio" name="tipo_atencion" value="ESTANDAR_EXPRESS" checked class="text-blue-600">
                            <span><strong>Fila Express</strong> (General)</span>
                        </label>
                        <label class="p-2.5 rounded-lg border border-slate-300 text-xs flex items-center gap-2 cursor-pointer bg-white hover:bg-slate-50">
                            <input type="radio" name="tipo_atencion" value="PRIORITARIA_ADULTO_MAYOR" class="text-blue-600">
                            <span><strong>Atención Prioritaria</strong> (60+ años / Embarazo)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Grilla de Bloques Horarios -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Seleccione su Turno Horario *</label>
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-2.5">
                    @foreach($slots as $index => $slot)
                        <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border-2 text-center cursor-pointer transition-all
                            {{ $slot['available'] ? ($index === 1 ? 'border-blue-600 bg-blue-50 text-blue-900 font-bold' : 'border-slate-200 bg-white hover:border-slate-300') : 'opacity-50 cursor-not-allowed bg-slate-100' }}">
                            <input type="radio"
                                   name="hora_cita"
                                   value="{{ $slot['time'] }}"
                                   {{ $index === 1 ? 'checked' : '' }}
                                   {{ ! $slot['available'] ? 'disabled' : '' }}
                                   class="sr-only">
                            <span class="text-xs font-bold font-mono">{{ $slot['time'] }}</span>
                            <span class="text-[10px] text-emerald-700 mt-0.5">
                                {{ $slot['remaining'] }} cupos
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 3. Checklist Obligatorio de Documentos Físicos -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-[#0F2C59] flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-bold">3</span>
                    <span>Lista de Verificación de Documentos Originales</span>
                </h2>
                <span class="text-xs text-rose-600 font-bold">Obligatorio en ventanilla</span>
            </div>

            <div class="space-y-3">
                <label class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input type="checkbox" name="checklist_dui" value="1" required checked class="mt-1 h-4 w-4 text-blue-600 rounded border-slate-300">
                    <div class="text-xs sm:text-sm text-slate-800">
                        <strong>Documento de Identidad Original en Físico:</strong>
                        <span class="text-slate-600 block text-xs">Presentaré mi DUI vigente o Carnet de Residente original en ventanilla (no fotocopia simple).</span>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input type="checkbox" name="checklist_licencia" value="1" required checked class="mt-1 h-4 w-4 text-blue-600 rounded border-slate-300">
                    <div class="text-xs sm:text-sm text-slate-800">
                        <strong>Licencia de Conducir Anterior en Físico:</strong>
                        <span class="text-slate-600 block text-xs">Entregaré la credencial anterior a renovar para su perforación y sustitución.</span>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input type="checkbox" name="checklist_lentes" value="1" class="mt-1 h-4 w-4 text-blue-600 rounded border-slate-300">
                    <div class="text-xs sm:text-sm text-slate-800">
                        <strong>Lentes Graduados (Optométricos):</strong>
                        <span class="text-slate-600 block text-xs">Marque esta casilla si utiliza lentes de prescripción médica para conducir, a fin de evaluar su agudeza con ellos en la prueba.</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Botones de Navegación -->
        <div class="pt-4 border-t border-slate-200 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
            <a href="{{ route('tramite.datos-y-pago') }}"
               class="w-full sm:w-auto px-5 py-2.5 text-center text-sm font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 rounded-xl transition-colors">
                ← Volver al Paso 3: Pago
            </a>

            <button type="submit"
                    class="w-full sm:w-auto px-6 py-3 text-center text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition-all focus:outline-none focus:ring-4 focus:ring-blue-300 flex items-center justify-center gap-2">
                <span>Confirmar Cita y Generar Comprobante Oficial</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection
