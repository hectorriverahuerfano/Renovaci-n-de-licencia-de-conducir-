@props([
    'type' => 'esquela', // 'esquela', 'api_timeout', 'bloqueo_legal'
    'title' => null,
    'description' => null,
    'esquelaId' => null,
    'referenceNumber' => null,
    'amount' => null,
    'recheckAction' => null
])

@php
    $isEsquela = $type === 'esquela';
    $isTimeout = $type === 'api_timeout';
    $isLegal = $type === 'bloqueo_legal';

    $borderColor = $isEsquela ? 'border-amber-500' : ($isTimeout ? 'border-blue-500' : 'border-rose-500');
    $bgColor = $isEsquela ? 'bg-amber-50/90' : ($isTimeout ? 'bg-blue-50/90' : 'bg-rose-50/90');
    $iconColor = $isEsquela ? 'text-amber-600' : ($isTimeout ? 'text-blue-600' : 'text-rose-600');
    $headingColor = $isEsquela ? 'text-amber-900' : ($isTimeout ? 'text-blue-900' : 'text-rose-900');
@endphp

<div role="alert" aria-live="assertive" class="rounded-xl border-2 {{ $borderColor }} {{ $bgColor }} p-5 md:p-6 shadow-sm my-6 transition-all">
    <div class="flex items-start gap-4">
        <div class="shrink-0 {{ $iconColor }} mt-0.5">
            @if($isTimeout)
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif($isLegal)
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @else
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            @endif
        </div>

        <div class="flex-1">
            <h3 class="text-base md:text-lg font-bold {{ $headingColor }} mb-1">
                {{ $title ?? ($isEsquela ? 'Infracción de tránsito pendiente de pago detectada en el VMT' : 'Interrupción temporal del servicio') }}
            </h3>

            <p class="text-sm text-slate-700 mb-3 leading-relaxed">
                {{ $description }}
            </p>

            @if($isEsquela && $referenceNumber)
                <div class="bg-white border border-amber-200 rounded-lg p-3.5 mb-4 text-xs md:text-sm text-slate-800 flex flex-wrap items-center justify-between gap-3 shadow-2xs">
                    <div>
                        <span class="text-slate-500 block text-xs">Registro Oficial VMT:</span>
                        <strong class="font-mono text-slate-900">{{ $referenceNumber }}</strong>
                    </div>
                    @if($amount)
                        <div>
                            <span class="text-slate-500 block text-xs">Monto a solventar:</span>
                            <span class="text-red-700 font-extrabold text-base">US$ {{ number_format($amount, 2) }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-3 pt-1">
                @if($isEsquela && $esquelaId)
                    <!-- Botón A: Pagar en línea ahora -->
                    <form action="{{ route('tramite.pagar-esquela') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="esquela_id" value="{{ $esquelaId }}">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Pagar Esquela en Línea Ahora (US$ {{ number_format($amount, 2) }})
                        </button>
                    </form>

                    <!-- Botón B: Ya pagué / Revalidar -->
                    <button type="button"
                            onclick="document.getElementById('form-identificacion').submit()"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-800 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0F2C59] transition-colors">
                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Ya pagué — Revalidar Solvencia VMT
                    </button>
                @endif

                @if($isTimeout)
                    <!-- Botón Reintentar para Timeout -->
                    <button type="button"
                            onclick="document.getElementById('simular_falla_api').checked = false; document.getElementById('form-identificacion').submit();"
                            class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reintentar Consulta VMT
                    </button>
                @endif

                <!-- Botón de Atención Asistida / Modal accesible sin ventanas nativas -->
                <button type="button"
                        onclick="window.openGoesModal('atencion-asistida-modal')"
                        class="inline-flex items-center text-sm font-semibold text-[#0F2C59] hover:text-blue-800 underline focus:outline-none focus:ring-2 focus:ring-blue-600 rounded px-2 py-1">
                    <svg class="w-4 h-4 mr-1.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Solicitar Atención Asistida / Orientación Oficial
                </button>
            </div>
        </div>
    </div>
</div>
