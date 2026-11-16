<?php

namespace App\Http\Controllers;

use App\Models\CiudadanoDemo;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    /**
     * Muestra la vista de seguimiento del trámite ciudadano.
     */
    public function index(): View
    {
        return view('seguimiento.index');
    }

    /**
     * Busca la solicitud por código único o número de documento y presenta el estado sin revelar PII.
     */
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'criterio' => ['required', 'string', 'max:50'],
        ], [
            'criterio.required' => 'Ingrese el código de solicitud (GOES-RN-XXXXXXXX) o su número de DUI.',
        ]);

        $termino = trim($validated['criterio']);

        // Buscar por código de solicitud o por documento del ciudadano
        $solicitud = Solicitud::with(['ciudadano', 'licencia', 'cita.sucursal', 'pagos'])
            ->where('codigo_solicitud', $termino)
            ->orWhereHas('ciudadano', function ($query) use ($termino) {
                $query->where('numero_documento', $termino);
            })
            ->latest()
            ->first();

        return view('seguimiento.index', [
            'busquedaRealizada' => true,
            'termino' => $termino,
            'solicitud' => $solicitud,
        ]);
    }
}
