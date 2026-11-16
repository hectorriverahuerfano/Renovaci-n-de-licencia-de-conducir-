@props([
    'name',
    'title' => 'Mensaje del Sistema',
    'maxWidth' => '2xl'
])

@php
$maxWidthClass = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth] ?? 'max-w-2xl';
@endphp

<div id="modal-{{ $name }}"
     class="goes-accessible-modal fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center hidden"
     role="dialog"
     aria-modal="true"
     aria-labelledby="{{ $name }}-title"
     data-modal-name="{{ $name }}">
    
    <!-- Backdrop translúcido accesible -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
         onclick="window.closeGoesModal('{{ $name }}')"
         aria-hidden="true"></div>

    <!-- Panel Modal -->
    <div class="relative bg-white rounded-xl overflow-hidden shadow-2xl transform transition-all w-full {{ $maxWidthClass }} border border-slate-200 z-10 my-8">
        <!-- Cabecera -->
        <div class="px-6 py-4 bg-[#0F2C59] text-white flex items-center justify-between">
            <h2 id="{{ $name }}-title" class="text-lg font-bold font-['Plus_Jakarta_Sans',sans-serif]">{{ $title }}</h2>
            <button type="button"
                    onclick="window.closeGoesModal('{{ $name }}')"
                    class="text-slate-300 hover:text-white rounded-lg p-1.5 focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Cerrar ventana emergente">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Contenido -->
        <div class="p-6 text-slate-800 text-sm max-h-[75vh] overflow-y-auto">
            {{ $slot }}
        </div>

        <!-- Acciones del pie de modal -->
        @if(isset($footer))
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex flex-wrap justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
