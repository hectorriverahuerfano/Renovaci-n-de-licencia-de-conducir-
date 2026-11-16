<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciudadanos_demo', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 32); // DUI, RESIDENTE_TEMPORAL, RESIDENTE_DEFINITIVO, DIPLOMATICO
            $table->string('numero_documento', 32)->unique()->index();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento');
            $table->string('correo', 150)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->date('vigencia_documento_hasta')->nullable(); // Para validar > 3 meses en residente temporal
            $table->boolean('es_simulacion_falla')->default(false); // Simulación de caída de API externa
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciudadanos_demo');
    }
};
