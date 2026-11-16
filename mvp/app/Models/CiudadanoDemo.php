<?php

namespace App\Models;

use App\Enums\TipoDocumento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CiudadanoDemo extends Model
{
    use HasFactory;

    protected $table = 'ciudadanos_demo';

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'correo',
        'telefono',
        'vigencia_documento_hasta',
        'es_simulacion_falla',
    ];

    protected $casts = [
        'tipo_documento' => TipoDocumento::class,
        'fecha_nacimiento' => 'date',
        'vigencia_documento_hasta' => 'date',
        'es_simulacion_falla' => 'boolean',
    ];

    public function nombreCompleto(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function licencias(): HasMany
    {
        return $this->hasMany(Licencia::class, 'ciudadano_id');
    }

    public function licenciaPrincipal(): HasOne
    {
        return $this->hasOne(Licencia::class, 'ciudadano_id')->latestOfMany();
    }

    public function esquelas(): HasMany
    {
        return $this->hasMany(Esquela::class, 'ciudadano_id');
    }

    public function esquelasPendientes(): HasMany
    {
        return $this->hasMany(Esquela::class, 'ciudadano_id')->where('estado', 'PENDIENTE');
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'ciudadano_id');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'ciudadano_id');
    }
}
