@props([
    'currentStep' => 1,
    'steps' => [
        1 => 'Requisitos y Elegibilidad',
        2 => 'Identificación y Multas',
        3 => 'Confirmación y Pago',
        4 => 'Cita Examen Visual',
        5 => 'Comprobante y QR'
    ]
])

<nav aria-label="Progreso del trámite de renovación" class="w-full bg-white rounded-xl p-4 md:p-5 border border-slate-200 shadow-sm mb-6">
    <ol class="flex flex-col md:flex-row items-center justify-between gap-3 md:gap-0">
        @foreach($steps as $number => $title)
            @php
                $isCompleted = $number < $currentStep;
                $isCurrent = $number == $currentStep;
                $isPending = $number > $currentStep;
            @endphp
            <li class="flex items-center w-full md:w-auto {{ !$loop->last ? 'md:flex-1' : '' }}"
                @if($isCurrent) aria-current="step" @endif>
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-9 h-9 rounded-full text-sm font-bold transition-all duration-200
                        {{ $isCompleted ? 'bg-emerald-600 text-white shadow-sm' : '' }}
                        {{ $isCurrent ? 'bg-[#0F2C59] text-white ring-4 ring-blue-100 shadow-md' : '' }}
                        {{ $isPending ? 'bg-slate-100 text-slate-500 border border-slate-300' : '' }}"
                        aria-hidden="true">
                        @if($isCompleted)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            {{ $number }}
                        @endif
                    </span>
                    <span class="text-sm {{ $isCurrent ? 'font-bold text-[#0F2C59]' : ($isCompleted ? 'font-medium text-slate-700' : 'text-slate-500') }}">
                        <span class="sr-only">Paso {{ $number }}: </span>{{ $title }}
                    </span>
                </div>
                @if(!$loop->last)
                    <div class="hidden md:block flex-1 h-0.5 mx-4 {{ $isCompleted ? 'bg-emerald-500' : 'bg-slate-200' }}" aria-hidden="true"></div>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
