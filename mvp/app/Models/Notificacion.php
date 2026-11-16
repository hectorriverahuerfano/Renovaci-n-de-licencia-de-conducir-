<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'ciudadano_id',
        'solicitud_id',
        'canal',
        'tipo',
        'destinatario',
        'mensaje',
        'estado',
    ];

    public function ciudadano(): BelongsTo
    {
        return $this->belongsTo(CiudadanoDemo::class, 'ciudadano_id');
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }
}
