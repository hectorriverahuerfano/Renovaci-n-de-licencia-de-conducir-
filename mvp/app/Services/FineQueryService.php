<?php

namespace App\Services;

use App\Exceptions\ExternalServiceTimeoutException;
use App\Models\CiudadanoDemo;
use App\Models\Esquela;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FineQueryService
{
    /**
     * Consulta el estado de solvencia de multas en el VMT.
     * Si la integración simula falla (o se fuerza por toggle), dispara ExternalServiceTimeoutException.
     *
     * @throws ExternalServiceTimeoutException
     */
    public function checkSolvency(CiudadanoDemo $ciudadano, bool $forceSimulateFailure = false): array
    {
        if ($forceSimulateFailure || $ciudadano->es_simulacion_falla) {
            throw new ExternalServiceTimeoutException(
                'No se pudo establecer conexión con el Sistema Nacional de Infracciones del VMT. Tiempo de espera agotado.'
            );
        }

        $esquelasPendientes = Esquela::where('ciudadano_id', $ciudadano->id)
            ->where('estado', 'PENDIENTE')
            ->get();

        $tieneMultas = $esquelasPendientes->isNotEmpty();
        $totalMonto = $esquelasPendientes->sum('monto');

        return [
            'solvente' => ! $tieneMultas,
            'cantidad_esquelas' => $esquelasPendientes->count(),
            'total_monto' => (float) $totalMonto,
            'esquelas' => $esquelasPendientes,
        ];
    }

    /**
     * Simula el pago inmediato en línea de una esquela para desbloqueo sin perder la sesión.
     */
    public function payFine(int $esquelaId): Esquela
    {
        $esquela = Esquela::findOrFail($esquelaId);
        $esquela->update([
            'estado' => 'PAGADA',
            'fecha_pago' => Carbon::now(),
            'transaccion_pago_ref' => 'VMT-PAY-' . strtoupper(Str::random(10)),
        ]);

        return $esquela;
    }
}
