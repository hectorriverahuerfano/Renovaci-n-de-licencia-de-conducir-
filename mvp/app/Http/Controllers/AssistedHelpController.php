<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssistedRequest;
use App\Models\Notificacion;
use Illuminate\Http\RedirectResponse;

class AssistedHelpController extends Controller
{
    /**
     * Registra un ticket o solicitud de atención asistida en notificaciones.
     */
    public function store(AssistedRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $ticket = 'AYUDA-' . date('Ymd') . '-' . mt_rand(1000, 9999);

        Notificacion::create([
            'solicitud_id' => $validated['solicitud_id'] ?? null,
            'canal' => 'WEB_ASISTIDA',
            'tipo' => 'ATENCION_ASISTIDA',
            'destinatario' => $validated['telefono_contacto'] . ($validated['correo_contacto'] ? ' / ' . $validated['correo_contacto'] : ''),
            'mensaje' => "[Ticket {$ticket}] Motivo: {$validated['motivo']}. Contacto: {$validated['nombre_contacto']} ({$validated['telefono_contacto']}). Descripción: {$validated['descripcion']}",
            'estado' => 'REGISTRADO',
        ]);

        return back()->with('ayuda_registrada_exito', [
            'ticket' => $ticket,
            'nombre' => $validated['nombre_contacto'],
            'telefono' => $validated['telefono_contacto'],
            'mensaje' => 'Su solicitud de asistencia ha sido canalizada al Centro de Soporte Ciudadano del GOES. Un operador le contactará en breve o puede comunicarse al 2200-GOES con su número de ticket.',
        ]);
    }
}
