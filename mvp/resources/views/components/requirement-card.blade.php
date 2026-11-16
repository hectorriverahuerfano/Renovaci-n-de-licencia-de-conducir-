@props([
    'title',
    'badge' => null,
    'badgeColor' => 'bg-blue-100 text-blue-800',
    'cost' => null,
    'icon' => null,
])

<article class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
    <div>
        <div class="flex items-start justify-between gap-3 mb-2">
            <h3 class="text-base font-bold text-[#0F2C59]">{{ $title }}</h3>
            @if($badge)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeColor }}">
                    {{ $badge }}
                </span>
            @endif
        </div>
        <div class="text-sm text-slate-600 mb-3">
            {{ $slot }}
        </div>
    </div>
    @if($cost)
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Arancel fijado por ley:</span>
            <span class="font-bold text-slate-800 text-sm">{{ $cost }}</span>
        </div>
    @endif
</article>
