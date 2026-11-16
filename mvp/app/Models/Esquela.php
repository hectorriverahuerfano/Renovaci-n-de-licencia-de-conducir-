<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Esquela extends Model
{
    use HasFactory;

    protected $table = 'esquelas';

    protected $fillable = [
        'ciudadano_id',
        'numero_esquela',
        'infraccion_codigo',
        'tipo_falta',
        'descripcion',
        'departamento',
        'fecha_infraccion',
        'monto',
        'estado',
        'fecha_pago',
        'transaccion_pago_ref',
    ];

    protected $casts = [
        'fecha_infraccion' => 'date',
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2',
    ];

    public function ciudadano(): BelongsTo
    {
        return $this->belongsTo(CiudadanoDemo::class, 'ciudadano_id');
    }

    public function estaPendiente(): bool
    {
        return $this->estado === 'PENDIENTE';
    }
}
