@extends('layouts.app')

@section('title', 'Consulta y Seguimiento del Trámite de Licencia — GOES')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Encabezado de la Sección -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
        <div class="border-b border-slate-100 pb-4">
            <span class="text-xs font-bold text-blue-700 uppercase tracking-wider block mb-1">
                Servicios al Ciudadano • Portal de Transparencia VMT
            </span>
            <h1 class="text-xl sm:text-2xl font-bold text-[#0F2C59] font-heading">
                Consulta y Seguimiento de su Renovación de Licencia
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                Verifique en qué fase se encuentra su solicitud sin necesidad de iniciar sesión, ingresando su código único de trámite o su número de DUI.
            </p>
        </div>

        <!-- Formulario de Consulta -->
        <form action="{{ route('seguimiento.buscar') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="criterio" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Código de Solicitud (GOES-RN-XXXXXXXX) o Número de DUI *
                </label>
                <div class="flex flex-col sm:flex-row gap-2.5">
                    <input type="text"
                           id="criterio"
                           name="criterio"
                           value="{{ old('criterio', $termino ?? request('criterio', '')) }}"
                           required
                           class="flex-1 px-4 py-3 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                           placeholder="Ej. GOES-RN-20261116-8921 o 02489123-4">

                    <button type="submit"
                            class="px-6 py-3 rounded-xl font-bold text-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-xs flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 1114 0z"/></svg>
                        <span>Consultar Trámite</span>
                    </button>
                </div>

                @error('criterio')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </div>

    <!-- Resultados de la Búsqueda -->
    @if(isset($busquedaRealizada))
        @if($solicitud)
            <div class="bg-white rounded-2xl border-2 border-slate-200 p-6 sm:p-8 shadow-md space-y-5 animate-fade-in">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 uppercase block">Trámite Encontrado:</span>
                        <strong class="text-base font-mono text-blue-900 font-extrabold">{{ $solicitud->codigo_solicitud }}</strong>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                        {{ $solicitud->estado->value === 'CITA_AGENDADA' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $solicitud->estado->label() }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs sm:text-sm bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <div>
                        <span class="text-slate-500 block text-xs">Contribuyente:</span>
                        <strong class="text-slate-900">{{ $solicitud->ciudadano->nombreCompleto() }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs">Clase Solicitada:</span>
                        <strong class="text-slate-900">{{ $solicitud->clase_tramite }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs">Total Aranceles:</span>
                        <strong class="text-slate-900 font-mono">US$ {{ number_format($solicitud->monto_total, 2) }}</strong>
                    </div>
                </div>

                @if($solicitud->cita)
                    <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 text-xs sm:text-sm text-slate-800 space-y-1.5">
                        <span class="font-bold text-[#0F2C59] block">📍 Cita en SERTRACEN Confirmada:</span>
                        <p>
                            Sede: <strong>{{ $solicitud->cita->sucursal->nombre }}</strong>
                        </p>
                        <p>
                            Fecha y Turno: <strong>{{ $solicitud->cita->fecha_cita->format('d/m/Y') }}</strong> a las <strong class="font-mono text-blue-800">{{ $solicitud->cita->hora_cita }}</strong> (Modalidad: {{ $solicitud->cita->tipo_atencion }}).
                        </p>
                    </div>
                @endif

                <div class="pt-2 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100">
                    <a href="{{ route('tramite.comprobante', ['codigo' => $solicitud->codigo_solicitud]) }}"
                       class="inline-flex items-center px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-xs">
                        Ver Pase Digital Fila Express y QR Oficial →
                    </a>
                    <span class="text-xs text-slate-500">
                        Última actualización: {{ $solicitud->updated_at->format('d/m/Y h:i A') }}
                    </span>
                </div>
            </div>
        @else
            <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-6 text-center space-y-3">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 text-amber-700 mb-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-base font-bold text-amber-900">
                    No se encontró ningún trámite registrado con el criterio "{{ $termino }}"
                </h2>
                <p class="text-xs text-slate-600 max-w-md mx-auto">
                    Verifique que el código o DUI haya sido ingresado con el guion correspondiente, o inicie una nueva solicitud.
                </p>
                <div class="pt-2 flex justify-center gap-3">
                    <a href="{{ route('inicio') }}" class="px-4 py-2 text-xs font-bold text-white bg-[#0F2C59] hover:bg-blue-900 rounded-lg">
                        Iniciar Trámite de Renovación
                    </a>
                    <button type="button" onclick="window.openGoesModal('atencion-asistida-modal')" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-100 rounded-lg">
                        Solicitar Atención Asistida
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
