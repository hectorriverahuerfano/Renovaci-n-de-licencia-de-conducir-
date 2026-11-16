@props([
    'events' => []
])

<div class="flow-root my-6">
    <ol class="-mb-8">
        @foreach($events as $event)
            @php
                $isCompleted = $event['completed'] ?? false;
                $isCurrent = $event['current'] ?? false;
                $isPending = ! $isCompleted && ! $isCurrent;
            @endphp
            <li>
                <div class="relative pb-8">
                    @if(!$loop->last)
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 {{ $isCompleted ? 'bg-emerald-500' : 'bg-slate-200' }}" aria-hidden="true"></span>
                    @endif
                    <div class="relative flex items-start space-x-3.5">
                        <div>
                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                {{ $isCompleted ? 'bg-emerald-600 text-white' : '' }}
                                {{ $isCurrent ? 'bg-[#0F2C59] text-white ring-4 ring-blue-100' : '' }}
                                {{ $isPending ? 'bg-slate-100 text-slate-400 border border-slate-300' : '' }}">
                                @if($isCompleted)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @elseif($isCurrent)
                                    <span class="h-2.5 w-2.5 bg-amber-300 rounded-full animate-ping"></span>
                                @else
                                    <span class="h-2 w-2 bg-slate-300 rounded-full"></span>
                                @endif
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-0.5">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline gap-1">
                                <p class="text-sm font-bold {{ $isCurrent ? 'text-[#0F2C59]' : ($isCompleted ? 'text-slate-800' : 'text-slate-500') }}">
                                    {{ $event['title'] }}
                                </p>
                                @if(isset($event['date']))
                                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $event['date'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs md:text-sm text-slate-600">
                                {{ $event['description'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ol>
</div>
