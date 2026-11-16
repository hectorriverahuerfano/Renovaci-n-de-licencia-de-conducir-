<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciudadano_id')->constrained('ciudadanos_demo')->cascadeOnDelete();
            $table->string('numero_licencia', 32)->unique()->index();
            $table->string('clase', 32); // PARTICULAR, LIVIANA, etc.
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->string('estado', 20)->default('VIGENTE'); // VIGENTE, VENCIDA, SUSPENDIDA
            $table->boolean('donante_organos')->default(false);
            $table->string('tipo_sangre', 10)->default('O+');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licencias');
    }
};
