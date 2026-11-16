# Acta de Refinamiento Técnico-Funcional — Renovación de Licencia de Conducir (GOES)

- **Product Owner Lead:** Simon (`simon-product-owner`)
- **Fecha:** 2026-11-16
- **Participantes del Squad:** Kira (Arquitectura), Charlotte (UX/UI WCAG 2.2), Luna (Desarrollo Web Laravel), Max (Seguridad AppSec), Coco (Testing & QA), Oreo (Producción & Render)
- **Stack Acordado:** PHP 8.2+ / Laravel 11+ / SQLite / Render (Docker + Persistent Disk)
- **Tablero Azure Boards:** `https://dev.azure.com/AppProyectDevep/licencia%20de%20conducir%20en%20El%20Salvado`

---

## 1. Decisiones de Arquitectura y Datos (Kira)
- **Patrón de Capas en Laravel:** `FormRequests` (validación estricta) → `DTOs` (`readonly`) → `Services` (`EligibilityService`, `FineQueryService`, `PaymentProcessingService`, `AppointmentBookingService`, `StaffReviewService`) → `Repositories` (desacoplamiento Eloquent) → `API Resources`.
- **Interfaces de Desacoplamiento (MVP vs. Fase 2):** `FinesGatewayInterface` y `PaymentGatewayInterface` permiten operar con simuladores determinísticos en SQLite durante el MVP y conectar APIs reales del RNPN/VMT/Pasarela sin reescribir lógica de negocio.
- **Esquema SQLite:** `ciudadanos_demo`, `licencias`, `esquelas`, `solicitudes` (con código opaco `GOES-RN-XXXXXXXX`), `pagos`, `citas`, `notificaciones`, `auditoria_asistida`.

## 2. Decisiones de Diseño UX/UI y Accesibilidad (Charlotte)
- **Sistema de Componentes Blade Accesibles (WCAG 2.2 AA):** `<x-stepper>` (5+ pasos), `<x-requirement-card>`, `<x-modal>` (foco atrapado y cierre por Escape; **prohibidas las ventanas nativas** `alert`, `confirm`, `prompt`), `<x-alert-recovery>`, `<x-privacy-banner>` y `<x-assisted-help-button>` persistente.
- **Optimización para baja conectividad:** Vistas Blade ligeras, conservación de entrada (`old()`/sesión) y hojas de comprobante con `@media print`.

## 3. Decisiones de Seguridad y QA (Max & Coco)
- **AppSec:** `VerifyCsrfToken`, cookies `HttpOnly`/`Secure`/`SameSite=Strict`, validación de DUI salvadoreño módulo 10, `throttle:5,1` en consultas sensibles, cero PII en logs o URLs, Security Headers y SQLite fuera de `public/` en modo `WAL` (`chmod 664/600`).
- **QA:** Tests Unit y Feature en Pest/PHPUnit + pruebas E2E de caja negra en Playwright (sin atajos de URL) cubriendo: flujo feliz completo, multa pendiente recuperable, falla de API recuperable y derivación a atención asistida.

## 4. Decisiones de Despliegue en Render (Oreo)
- **Dockerfile Multi-Stage** (Node Vite → Composer → PHP 8.2/8.3 FPM Alpine + Nginx/Supervisor bajo `www-data`).
- **Persistencia SQLite:** Render Disk montado en `/var/data` (`DB_DATABASE=/var/data/database.sqlite`), `docker-entrypoint.sh` idempotente (`migrate --force`, `db:seed --force`), `APP_DEBUG=false`, `LOG_CHANNEL=stderr` y `healthCheckPath: /up`.
