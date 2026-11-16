<?php

namespace App\Models;

use App\Enums\CitaEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'solicitud_id',
        'sucursal_id',
        'fecha_cita',
        'hora_cita',
        'tipo_atencion',
        'estado',
        'checklist_dui_confirmado',
        'checklist_licencia_confirmada',
        'checklist_lentes_confirmado',
    ];

    protected $casts = [
        'estado' => CitaEstado::class,
        'fecha_cita' => 'date',
        'checklist_dui_confirmado' => 'boolean',
        'checklist_licencia_confirmada' => 'boolean',
        'checklist_lentes_confirmado' => 'boolean',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
