<?php

namespace App\Models;

use App\Enums\ClaseLicencia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Licencia extends Model
{
    use HasFactory;

    protected $table = 'licencias';

    protected $fillable = [
        'ciudadano_id',
        'numero_licencia',
        'clase',
        'fecha_emision',
        'fecha_vencimiento',
        'estado',
        'donante_organos',
        'tipo_sangre',
    ];

    protected $casts = [
        'clase' => ClaseLicencia::class,
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'donante_organos' => 'boolean',
    ];

    public function ciudadano(): BelongsTo
    {
        return $this->belongsTo(CiudadanoDemo::class, 'ciudadano_id');
    }

    public function estaVencida(): bool
    {
        return $this->fecha_vencimiento->isPast();
    }

    public function estaEnMesDeVencimiento(): bool
    {
        $now = Carbon::now();
        return $this->fecha_vencimiento->year === $now->year
            && $this->fecha_vencimiento->month === $now->month;
    }

    public function aplicaMoraExtemporanea(): bool
    {
        // Si está vencida y además no está en el mes corriente de vencimiento
        return $this->estaVencida() && ! $this->estaEnMesDeVencimiento();
    }
}
