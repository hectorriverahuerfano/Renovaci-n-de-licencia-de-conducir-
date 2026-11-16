<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $fillable = [
        'codigo',
        'nombre',
        'direccion',
        'departamento',
        'es_recomendada',
        'es_prioritaria',
        'capacidad_por_hora',
        'activo',
    ];

    protected $casts = [
        'es_recomendada' => 'boolean',
        'es_prioritaria' => 'boolean',
        'activo' => 'boolean',
        'capacidad_por_hora' => 'integer',
    ];

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'sucursal_id');
    }
}
