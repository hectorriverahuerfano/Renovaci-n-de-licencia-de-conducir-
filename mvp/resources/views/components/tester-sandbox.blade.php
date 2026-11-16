@props([
    'casos' => []
])

<div class="bg-slate-900 text-white border-b border-slate-700 py-2.5 px-4 text-xs font-sans">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-400 text-slate-950 uppercase tracking-wider">
                Tester Sandbox GOES
            </span>
            <span class="text-slate-300 font-medium hidden sm:inline">
                Entorno de Evaluación Rápida — 4 Escenarios Oficiales Idempotentes:
            </span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Caso 1 -->
            <a href="{{ route('tramite.identificacion', ['tipo_doc' => 'DUI', 'num_doc' => '02489123-4']) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-800 hover:bg-emerald-700 text-emerald-300 hover:text-white border border-slate-700 transition-colors"
               title="DUI 02489123-4: Salvadoreño solvente, mes de vigencia, arancel US$ 56.21">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="font-semibold">1. Flujo Feliz</span>
                <span class="text-slate-400 text-[10px] hidden md:inline">(02489123-4)</span>
            </a>

            <!-- Caso 2 -->
            <a href="{{ route('tramite.identificacion', ['tipo_doc' => 'DUI', 'num_doc' => '05123456-7']) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-800 hover:bg-amber-700 text-amber-300 hover:text-white border border-slate-700 transition-colors"
               title="DUI 05123456-7: Esquela US$ 34.29 + mora tardía US$ 5.71">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span class="font-semibold">2. Multa Pendiente</span>
                <span class="text-slate-400 text-[10px] hidden md:inline">(05123456-7)</span>
            </a>

            <!-- Caso 3 -->
            <a href="{{ route('tramite.identificacion', ['tipo_doc' => 'DUI', 'num_doc' => '09876543-2', 'simular_falla' => '1']) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-800 hover:bg-rose-700 text-rose-300 hover:text-white border border-slate-700 transition-colors"
               title="DUI 09876543-2: Simula desconexión de API VMT con recuperación asistida">
                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                <span class="font-semibold">3. Caída API VMT</span>
                <span class="text-slate-400 text-[10px] hidden md:inline">(09876543-2)</span>
            </a>

            <!-- Caso 4 -->
            <a href="{{ route('tramite.identificacion', ['tipo_doc' => 'RESIDENTE_TEMPORAL', 'num_doc' => 'RES-TMP-2026-01']) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-800 hover:bg-indigo-700 text-indigo-300 hover:text-white border border-slate-700 transition-colors"
               title="RES-TMP-2026-01: Carnet con < 3 meses (bloqueo legal con fundamento normativo)">
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                <span class="font-semibold">4. Bloqueo Normativo</span>
                <span class="text-slate-400 text-[10px] hidden md:inline">(RES-TMP)</span>
            </a>

            <!-- Enlace al Entregable Oficial PO -->
            <a href="/entregable-po.html"
               target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-blue-600 hover:bg-blue-500 text-white font-bold transition-all shadow-sm"
               title="Ver el documento completo de la prueba práctica con las 5 secciones respondidas">
                <span>📋 Entregable PO (10 Págs)</span>
            </a>
        </div>
    </div>
</div>
