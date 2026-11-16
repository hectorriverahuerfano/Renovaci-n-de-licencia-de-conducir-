<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('esquelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciudadano_id')->constrained('ciudadanos_demo')->cascadeOnDelete();
            $table->string('numero_esquela', 32)->unique()->index();
            $table->string('infraccion_codigo', 64); // Art. 117 VMT
            $table->string('tipo_falta', 30); // GRAVE, LEVE, MUY_GRAVE
            $table->string('descripcion', 255);
            $table->string('departamento', 100);
            $table->date('fecha_infraccion');
            $table->decimal('monto', 8, 2); // e.g. 34.29
            $table->string('estado', 20)->default('PENDIENTE'); // PENDIENTE, PAGADA, EN_APELACION
            $table->timestamp('fecha_pago')->nullable();
            $table->string('transaccion_pago_ref', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('esquelas');
    }
};
