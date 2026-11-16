<?php

use App\Http\Controllers\AssistedHelpController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — GOES Renovación de Licencia de Conducir (El Salvador)
|--------------------------------------------------------------------------
*/

Route::get('/', [RenewalController::class, 'index'])->name('inicio');

// Flujo principal del trámite de renovación (5 pasos)
Route::prefix('tramite')->name('tramite.')->group(function () {
    // Paso 2: Identificación y Solvencia VMT
    Route::get('/identificacion', [RenewalController::class, 'showIdentificacion'])->name('identificacion');
    Route::post('/identificacion', [RenewalController::class, 'postIdentificacion'])
        ->middleware('throttle:20,1')
        ->name('identificacion.post');

    // Recuperación interactiva de bloqueo por esquela
    Route::post('/pagar-esquela', [RenewalController::class, 'pagarEsquela'])->name('pagar-esquela');

    // Paso 3: Confirmación de datos personales y Pago
    Route::get('/datos-y-pago', [RenewalController::class, 'showDatosYPago'])->name('datos-y-pago');
    Route::post('/confirmar-datos', [RenewalController::class, 'postConfirmarDatos'])->name('confirmar-datos');
    Route::post('/pago', [RenewalController::class, 'postPago'])
        ->middleware('throttle:20,1')
        ->name('pago.post');

    // Paso 4: Cita en SERTRACEN y checklist
    Route::get('/cita', [RenewalController::class, 'showCita'])->name('cita');
    Route::post('/cita', [RenewalController::class, 'postCita'])->name('cita.post');

    // Paso 5: Comprobante oficial, pase QR Fila Express y reprogramación
    Route::get('/comprobante/{codigo}', [RenewalController::class, 'showComprobante'])->name('comprobante');
    Route::post('/reprogramar-cita/{codigo}', [RenewalController::class, 'reprogramarCita'])->name('reprogramar-cita');
});

// Seguimiento público del trámite (sin PII sensible)
Route::get('/seguimiento', [TrackingController::class, 'index'])->name('seguimiento');
Route::post('/seguimiento', [TrackingController::class, 'search'])
    ->middleware('throttle:20,1')
    ->name('seguimiento.buscar');

// Registro de Atención Asistida
Route::post('/atencion-asistida', [AssistedHelpController::class, 'store'])
    ->middleware('throttle:15,1')
    ->name('atencion-asistida.store');
