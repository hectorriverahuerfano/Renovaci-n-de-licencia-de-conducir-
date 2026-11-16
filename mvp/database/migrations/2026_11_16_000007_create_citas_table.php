<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();
            $table->date('fecha_cita');
            $table->string('hora_cita', 20); // '08:30', '10:00', etc.
            $table->string('tipo_atencion', 40)->default('ESTANDAR_EXPRESS'); // ESTANDAR_EXPRESS, PRIORITARIA_ADULTO_MAYOR
            $table->string('estado', 20)->default('PROGRAMADA'); // PROGRAMADA, ATENDIDA, REPROGRAMADA, CANCELADA
            $table->boolean('checklist_dui_confirmado')->default(true);
            $table->boolean('checklist_licencia_confirmada')->default(true);
            $table->boolean('checklist_lentes_confirmado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
