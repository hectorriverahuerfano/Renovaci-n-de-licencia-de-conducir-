@extends('layouts.app')

@section('title', 'Paso 1: Requisitos y Elegibilidad — Renovación de Licencia GOES')

@section('content')
<div class="space-y-8">
    <!-- Stepper de 5 Fases -->
    <x-stepper :currentStep="1" />

    <!-- Hero Institucional con Imagen Oficial -->
    <section class="relative bg-gradient-to-r from-[#0F2C59] to-[#1E3A8A] rounded-2xl overflow-hidden shadow-lg text-white">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center">
            <div class="p-6 sm:p-8 lg:p-10 lg:col-span-7 space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-blue-200 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Portal Digital Oficial — Viceministerio de Transporte</span>
                </div>
                
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold font-heading leading-tight">
                    Renueva tu Licencia de Conducir en Minutos
                </h1>
                
                <p class="text-slate-200 text-sm sm:text-base leading-relaxed">
                    Ahorra hasta 6 horas de fila. Inicia tu trámite 100% digital, verifica tu solvencia de multas en tiempo real, liquida los aranceles oficiales y agenda tu cita con atención garantizada en <strong>menos de 15 minutos</strong> por Fila Express en SERTRACEN.
                </p>

                <!-- Insignias de Eficiencia y Garantía -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                    <div class="bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/15">
                        <span class="text-xl sm:text-2xl font-black text-amber-300 block">5 min</span>
                        <span class="text-xs text-slate-300">Trámite digital</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/15">
                        <span class="text-xl sm:text-2xl font-black text-emerald-300 block">&lt; 15 min</span>
                        <span class="text-xs text-slate-300">En sede SERTRACEN</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-xs rounded-xl p-3 border border-white/15 col-span-2 sm:col-span-1">
                        <span class="text-xl sm:text-2xl font-black text-blue-200 block">US$ 56.21</span>
                        <span class="text-xs text-slate-300">Arancel oficial unificado</span>
                    </div>
                </div>

                <div class="pt-4 flex flex-wrap items-center gap-3">
                    <a href="{{ route('tramite.identificacion') }}"
                       class="inline-flex items-center px-6 py-3.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-500 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 text-base focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Comenzar Renovación Digital
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <button type="button"
                            onclick="window.openGoesModal('atencion-asistida-modal')"
                            class="inline-flex items-center px-4 py-3.5 rounded-xl font-semibold text-slate-100 hover:text-white bg-white/10 hover:bg-white/20 transition-colors text-sm">
                        📞 Solicitar Ayuda Asistida
                    </button>
                </div>
            </div>

            <div class="lg:col-span-5 h-64 lg:h-full relative min-h-[300px]">
                <img src="{{ asset('images/hero-licencia-goes.jpg') }}"
                     alt="Emisión de Licencia de Conducir en El Salvador"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-r from-[#0F2C59] via-transparent to-transparent"></div>
            </div>
        </div>
    </section>

    <!-- Banner de Privacidad de Datos -->
    <x-privacy-banner />

    <!-- Requisitos Legales y Condiciones Oficiales -->
    <section aria-labelledby="requisitos-heading" class="space-y-4">
        <div class="border-b border-slate-200 pb-3 flex items-center justify-between">
            <div>
                <h2 id="requisitos-heading" class="text-xl font-bold text-[#0F2C59]">
                    Requisitos y Condiciones Obligatorias (Ley de Tránsito)
                </h2>
                <p class="text-xs sm:text-sm text-slate-600">
                    Verifique que cumple con los 7 requisitos legales antes de iniciar el trámite.
                </p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-full hidden sm:inline-block">
                Decreto N° 456
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-requirement-card title="1. Documento de Identidad Original" badge="Obligatorio" badgeColor="bg-blue-100 text-blue-800">
                Salvadoreños: <strong>DUI vigente</strong> en original. Extranjeros: <strong>Carnet de Residente Definitivo o Temporal</strong> con más de 3 meses de vigencia restante, o Carnet Diplomático oficial.
            </x-requirement-card>

            <x-requirement-card title="2. Licencia Física a Renovar" badge="Física" badgeColor="bg-blue-100 text-blue-800">
                Debe presentar la licencia de conducir original anterior al momento de recibir la nueva credencial en la ventanilla de SERTRACEN.
            </x-requirement-card>

            <x-requirement-card title="3. Examen Visual Obligatorio" badge="En Sucursal" badgeColor="bg-indigo-100 text-indigo-800" cost="US$ 3.39">
                Por mandato de la Ley de Transporte, la presencia física del ciudadano es obligatoria para la prueba optométrica en consultorio SERTRACEN (5 minutos).
            </x-requirement-card>

            <x-requirement-card title="4. Solvente de Esquelas VMT" badge="0 Multas" badgeColor="bg-emerald-100 text-emerald-800">
                No tener multas pendientes de pago registradas en el Sistema Nacional del VMT. Si posee una, nuestro portal le permite solventarla en línea de inmediato.
            </x-requirement-card>

            <x-requirement-card title="5. Arancel de Trámite" badge="Arancel Ley" badgeColor="bg-blue-100 text-blue-800" cost="US$ 52.82">
                Pago de los derechos de renovación ante la Dirección General de Tesorería. Se puede pagar digitalmente con tarjeta, Chivo Wallet o mandamiento NPE.
            </x-requirement-card>

            <x-requirement-card title="6. Renovación en Mes de Vencimiento" badge="Sin Recargo" badgeColor="bg-amber-100 text-amber-800" cost="Mora: US$ 5.71">
                La renovación se puede realizar en cualquier momento durante el mes de cumpleaños o mes en que vence. Si ya caducó, se aplica una multa extemporánea de $5.71.
            </x-requirement-card>
        </div>
    </section>

    <!-- Selector de las 7 Clases de Licencia Oficiales -->
    <section aria-labelledby="clases-heading" class="space-y-4 pt-4">
        <div class="border-b border-slate-200 pb-3">
            <h2 id="clases-heading" class="text-xl font-bold text-[#0F2C59]">
                Seleccione la Clase de Licencia a Renovar
            </h2>
            <p class="text-xs sm:text-sm text-slate-600">
                En El Salvador existen 7 clases oficiales. La tarifa base es unificada a US$ 56.21 (US$ 52.82 trámite + US$ 3.39 examen visual).
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($clases as $clase)
                <div class="bg-white border-2 rounded-xl p-4 transition-all cursor-pointer hover:border-blue-600 hover:shadow-md
                    {{ $clase->value === 'PARTICULAR' ? 'border-blue-600 ring-2 ring-blue-100 bg-blue-50/20' : 'border-slate-200' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Vigencia: {{ $clase->vigenciaAnios() }} {{ $clase->vigenciaAnios() == 1 ? 'Año' : 'Años' }}
                        </span>
                        @if($clase->value === 'PARTICULAR')
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">
                                Más Común
                            </span>
                        @endif
                    </div>

                    <h3 class="text-base font-bold text-[#0F2C59] mb-1">
                        {{ $clase->shortName() }}
                    </h3>
                    
                    <p class="text-xs text-slate-600 mb-4 h-12 leading-relaxed">
                        {{ $clase->label() }}
                    </p>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500">Costo oficial:</span>
                        <span class="font-extrabold text-[#0F2C59] text-sm">US$ 56.21</span>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('tramite.identificacion', ['clase' => $clase->value]) }}"
                           class="w-full text-center block py-1.5 px-3 rounded-lg text-xs font-bold transition-colors
                           {{ $clase->value === 'PARTICULAR' ? 'bg-[#0F2C59] text-white hover:bg-blue-800' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Seleccionar y Continuar →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Casos de Prueba Rápidos para el Evaluador -->
    <section class="bg-slate-100 rounded-2xl p-6 border border-slate-300">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
            <h2 class="text-lg font-bold text-slate-900">
                Guía Rápida para el Evaluador Técnico (4 Escenarios Clave)
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($casosPrueba as $caso)
                <div class="bg-white rounded-xl p-4 border border-slate-200 flex flex-col justify-between shadow-2xs">
                    <div>
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border mb-2 {{ $caso['badge_color'] }}">
                            {{ $caso['badge'] }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">{{ $caso['titulo'] }}</h3>
                        <p class="text-xs text-slate-600 mb-3">{{ $caso['descripcion'] }}</p>
                    </div>
                    <a href="{{ route('tramite.identificacion', ['tipo_doc' => $caso['tipo'], 'num_doc' => $caso['dui'], 'simular_falla' => $caso['id'] === 'caso3' ? '1' : '0']) }}"
                       class="text-center py-1.5 px-2.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-800 hover:bg-blue-100 border border-blue-200 transition-colors">
                        Cargar este Caso de Prueba →
                    </a>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
