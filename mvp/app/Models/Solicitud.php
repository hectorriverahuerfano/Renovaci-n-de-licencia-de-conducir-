<?php

namespace App\Models;

use App\Enums\SolicitudEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'codigo_solicitud',
        'ciudadano_id',
        'licencia_id',
        'clase_tramite',
        'estado',
        'monto_tramite',
        'monto_examen_visual',
        'monto_mora',
        'monto_total',
        'correo_contacto',
        'telefono_contacto',
        'paso_actual',
    ];

    protected $casts = [
        'estado' => SolicitudEstado::class,
        'monto_tramite' => 'decimal:2',
        'monto_examen_visual' => 'decimal:2',
        'monto_mora' => 'decimal:2',
        'monto_total' => 'decimal:2',
        'paso_actual' => 'integer',
    ];

    public function ciudadano(): BelongsTo
    {
        return $this->belongsTo(CiudadanoDemo::class, 'ciudadano_id');
    }

    public function licencia(): BelongsTo
    {
        return $this->belongsTo(Licencia::class, 'licencia_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'solicitud_id');
    }

    public function pagoAprobado(): HasOne
    {
        return $this->hasOne(Pago::class, 'solicitud_id')->where('estado', 'APROBADO');
    }

    public function cita(): HasOne
    {
        return $this->hasOne(Cita::class, 'solicitud_id')->latestOfMany();
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'solicitud_id');
    }
}
