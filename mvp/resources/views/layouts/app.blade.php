<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Renovación de Licencia de Conducir') — Gobierno de El Salvador</title>
    
    <!-- Tipografías Oficiales GOES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN para renderizado instantáneo y responsivo -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        goes: {
                            navy: '#0F2C59',
                            blue: '#1D4ED8',
                            hover: '#1E40AF',
                            bg: '#F8FAFC',
                            success: '#15803D',
                            warning: '#B45309',
                            danger: '#B91C1C',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --font-size-base: 1rem;
        }
        body {
            font-size: var(--font-size-base);
            font-family: 'Inter', system-ui, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
        /* Modo Alto Contraste */
        body.high-contrast {
            background-color: #000000 !important;
            color: #FFFFFF !important;
        }
        body.high-contrast .bg-white {
            background-color: #121212 !important;
            color: #FFFFFF !important;
            border-color: #FFFF00 !important;
        }
        body.high-contrast .text-slate-600,
        body.high-contrast .text-slate-700,
        body.high-contrast .text-slate-800 {
            color: #E2E8F0 !important;
        }
        body.high-contrast a,
        body.high-contrast button {
            outline: 2px solid #FFFF00 !important;
        }

        /* Reglas @media print para comprobante en 1 sola página carta */
        @media print {
            .no-print, header, footer, aside, .tester-sandbox-bar {
                display: none !important;
            }
            body {
                background: #FFFFFF !important;
                color: #000000 !important;
                font-size: 11pt !important;
            }
            .printable-card {
                box-shadow: none !important;
                border: 1px solid #000000 !important;
                padding: 1cm !important;
                margin: 0 !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased">
    <!-- Enlace accesible de salto al contenido principal (WCAG 2.2 AA) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:px-4 focus:py-2 focus:bg-[#0F2C59] focus:text-white focus:rounded-md focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-white">
        Saltar directamente al contenido principal
    </a>

    <!-- Barra de Entorno de Evaluación (Tester Sandbox) -->
    <div class="tester-sandbox-bar no-print">
        <x-tester-sandbox />
    </div>

    <!-- TopBar de Accesibilidad Ciudadana (WCAG 2.2) -->
    <div class="no-print bg-[#0B1E3D] text-slate-200 text-xs py-1.5 px-4 border-b border-blue-950">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="font-medium">Portal Oficial de Trámites Digitales — República de El Salvador</span>
            </div>
            <div class="flex items-center gap-4">
                <!-- Controles de Tamaño de Fuente -->
                <div class="flex items-center gap-1" aria-label="Ajustar tamaño de letra">
                    <span class="text-slate-400 mr-1 hidden sm:inline">Texto:</span>
                    <button type="button" onclick="window.adjustFontSize(-1)" class="px-1.5 py-0.5 rounded bg-slate-800 hover:bg-slate-700 text-white font-bold" title="Reducir tamaño de letra" aria-label="Reducir tamaño de letra">A-</button>
                    <button type="button" onclick="window.adjustFontSize(0)" class="px-1.5 py-0.5 rounded bg-slate-800 hover:bg-slate-700 text-white font-bold" title="Tamaño de letra predeterminado" aria-label="Restablecer tamaño de letra">A</button>
                    <button type="button" onclick="window.adjustFontSize(1)" class="px-1.5 py-0.5 rounded bg-slate-800 hover:bg-slate-700 text-white font-bold" title="Aumentar tamaño de letra" aria-label="Aumentar tamaño de letra">A+</button>
                </div>
                <!-- Conmutador de Alto Contraste -->
                <button type="button" onclick="window.toggleHighContrast()" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-slate-800 hover:bg-slate-700 text-white text-[11px]" title="Alternar modo de alto contraste para baja visión">
                    <svg class="w-3.5 h-3.5 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12v12z"/></svg>
                    <span>Contraste</span>
                </button>
                <!-- Teléfono de Asistencia -->
                <a href="tel:22004637" class="text-amber-300 hover:underline font-semibold hidden md:inline">
                    📞 Ayuda: 2200-GOES
                </a>
            </div>
        </div>
    </div>

    <!-- Cabecera Institucional Principal -->
    <header class="no-print bg-[#0F2C59] text-white shadow-md sticky top-0 z-30 border-b border-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('inicio') }}" class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-amber-400 rounded-lg p-1">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center p-1.5 border border-white/20 group-hover:bg-white/20 transition-colors">
                        <!-- Escudo estilizado SVG -->
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5z"/>
                            <path d="M12 8v8"/>
                            <path d="M8 12h8"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block text-xs uppercase tracking-widest text-blue-200 font-bold">Gobierno de El Salvador</span>
                        <span class="block text-lg font-bold font-heading leading-tight group-hover:text-blue-100 transition-colors">
                            Viceministerio de Transporte <span class="font-light text-slate-300">| SERTRACEN</span>
                        </span>
                    </div>
                </a>
            </div>

            <nav class="flex items-center gap-3">
                <a href="{{ route('seguimiento') }}"
                   class="px-3.5 py-1.5 text-xs sm:text-sm font-semibold rounded-lg bg-white/10 hover:bg-white/20 text-white transition-colors border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    🔍 Consultar Trámite
                </a>
                <a href="{{ route('inicio') }}"
                   class="px-3.5 py-1.5 text-xs sm:text-sm font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-xs focus:outline-none focus:ring-2 focus:ring-white">
                    Nueva Renovación
                </a>
            </nav>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main id="main-content" class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        @if(session('success'))
            <div role="status" class="mb-5 p-4 bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg text-emerald-900 text-sm flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div role="status" class="mb-5 p-4 bg-blue-50 border-l-4 border-blue-600 rounded-r-lg text-blue-900 text-sm flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div role="alert" class="mb-5 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg text-amber-900 text-sm flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Modal Global de Atención Asistida (Sin Ventanas Nativas) -->
    <x-modal name="atencion-asistida-modal" title="Centro Oficial de Atención Ciudadana Asistida">
        <div class="space-y-4">
            <div class="flex items-center gap-4 bg-blue-50 p-3.5 rounded-lg border border-blue-200">
                <img src="{{ asset('images/ilustracion-atencion-asistida.jpg') }}" alt="Agente de Atención Asistida" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-xs">
                <div>
                    <h3 class="font-bold text-[#0F2C59] text-base">¿Tiene alguna dificultad o consulta con su trámite?</h3>
                    <p class="text-xs text-slate-600">
                        Nuestros delegados están disponibles para guiarle paso a paso, apelar observaciones o programar asistencia prioritaria sin ningún costo.
                    </p>
                </div>
            </div>

            <form action="{{ route('atencion-asistida.store') }}" method="POST" id="form-atencion-asistida" class="space-y-3.5">
                @csrf
                <input type="hidden" name="solicitud_id" value="{{ session('solicitud_id') }}">

                <div>
                    <label for="modal-nombre" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nombre Completo *</label>
                    <input type="text" id="modal-nombre" name="nombre_contacto" required class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border" placeholder="Ej. Carlos Monterrosa">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="modal-telefono" class="block text-xs font-bold text-slate-700 uppercase mb-1">Teléfono Móvil *</label>
                        <input type="tel" id="modal-telefono" name="telefono_contacto" required class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border" placeholder="Ej. 7845-1234">
                    </div>
                    <div>
                        <label for="modal-correo" class="block text-xs font-bold text-slate-700 uppercase mb-1">Correo Electrónico</label>
                        <input type="email" id="modal-correo" name="correo_contacto" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border" placeholder="carlos@ejemplo.com">
                    </div>
                </div>

                <div>
                    <label for="modal-motivo" class="block text-xs font-bold text-slate-700 uppercase mb-1">Motivo de Asistencia *</label>
                    <select id="modal-motivo" name="motivo" required class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border bg-white">
                        <option value="DIFICULTAD_DIGITAL">Dificultad técnica o poca familiaridad con el portal</option>
                        <option value="APELACION_ESQUELA">Aclaración o apelación sobre esquela de tránsito VMT</option>
                        <option value="ERROR_SISTEMA">Falla en la validación de DUI o pasarela de pago</option>
                        <option value="REPROGRAMACION">Reprogramación de cita presencial en SERTRACEN</option>
                        <option value="OTRO">Otra consulta normativa o ciudadana</option>
                    </select>
                </div>

                <div>
                    <label for="modal-descripcion" class="block text-xs font-bold text-slate-700 uppercase mb-1">Detalle de su solicitud *</label>
                    <textarea id="modal-descripcion" name="descripcion" rows="3" required class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 border" placeholder="Describa brevemente en qué podemos asistirle..."></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2.5">
                    <button type="button" onclick="window.closeGoesModal('atencion-asistida-modal')" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg shadow-sm">
                        Enviar Solicitud de Ayuda
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Botón Flotante Persistente de Atención Asistida -->
    <x-assisted-help-button />

    <!-- Pie de Página Institucional GOES -->
    <footer class="no-print bg-[#0B1E3D] text-slate-300 text-xs py-8 mt-12 border-t border-blue-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <span class="text-sm font-bold text-white block mb-2 font-heading">República de El Salvador</span>
                <p class="text-slate-400 leading-relaxed mb-3">
                    Iniciativa de Modernización y Digitalización Gubernamental liderada por el Viceministerio de Transporte (VMT), SERTRACEN y la Secretaría de Innovación de la Presidencia de la República.
                </p>
                <p class="text-[11px] text-slate-500">
                    Aranceles regulados por el Decreto Legislativo N° 456 y el Reglamento de Tránsito Terrestre.
                </p>
            </div>
            <div>
                <span class="text-sm font-bold text-white block mb-2 font-heading">Sedes y Horarios</span>
                <ul class="space-y-1.5 text-slate-400">
                    <li>Lunes a Viernes: 8:00 AM – 4:00 PM</li>
                    <li>Sábados: 8:00 AM – 12:00 MD</li>
                    <li>Las Cascadas • Central San Salvador</li>
                    <li>Santa Ana • San Miguel</li>
                </ul>
            </div>
            <div>
                <span class="text-sm font-bold text-white block mb-2 font-heading">Contacto Ciudadano</span>
                <ul class="space-y-1.5 text-slate-400">
                    <li>📞 Centro de Llamadas: <strong class="text-white">2200-4637</strong></li>
                    <li>💬 WhatsApp Oficial: <strong class="text-white">+503 7000-GOES</strong></li>
                    <li>✉️ Soporte: <strong class="text-white">atencion@vmt.gob.sv</strong></li>
                    <li>🛡️ Cumplimiento WCAG 2.2 Nivel AA</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pt-4 border-t border-slate-800 text-center text-slate-500 text-[11px]">
            © {{ date('Y') }} Gobierno de El Salvador. Todos los derechos reservados. Plataforma de Renovación de Licencias en Línea.
        </div>
    </footer>

    <!-- Scripts Accesibles Vanilla JS (Control de Foco, Modales y Accesibilidad) -->
    <script>
        // Gestor de Modales Accesibles (CERO VENTANAS NATIVAS)
        let lastFocusedElement = null;

        window.openGoesModal = function(name) {
            lastFocusedElement = document.activeElement;
            const modal = document.getElementById('modal-' + name);
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Foco en el primer elemento interactivo
            setTimeout(() => {
                const focusables = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (focusables.length) focusables[0].focus();
            }, 50);
        };

        window.closeGoesModal = function(name) {
            const modal = document.getElementById('modal-' + name);
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            if (lastFocusedElement) {
                lastFocusedElement.focus();
            }
        };

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                document.querySelectorAll('.goes-accessible-modal:not(.hidden)').forEach(modal => {
                    const name = modal.getAttribute('data-modal-name');
                    if (name) window.closeGoesModal(name);
                });
            }
        });

        // Controles de Tamaño de Fuente
        let currentZoomLevel = 0;
        window.adjustFontSize = function(delta) {
            if (delta === 0) currentZoomLevel = 0;
            else currentZoomLevel = Math.max(-1, Math.min(2, currentZoomLevel + delta));
            
            const sizes = ['0.9rem', '1rem', '1.125rem', '1.25rem'];
            document.documentElement.style.setProperty('--font-size-base', sizes[currentZoomLevel + 1]);
        };

        // Modo Alto Contraste
        window.toggleHighContrast = function() {
            document.body.classList.toggle('high-contrast');
        };
    </script>
    @stack('scripts')
</body>
</html>
