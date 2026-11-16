<?php

namespace App\Models;

use App\Enums\PagoEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'solicitud_id',
        'referencia_transaccion',
        'metodo_pago',
        'monto',
        'estado',
        'npe',
        'npe_vencimiento',
        'detalles_pasarela',
        'fecha_pago',
    ];

    protected $casts = [
        'estado' => PagoEstado::class,
        'monto' => 'decimal:2',
        'npe_vencimiento' => 'date',
        'detalles_pasarela' => 'array',
        'fecha_pago' => 'datetime',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }
}
