<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 32)->unique();
            $table->string('nombre', 120);
            $table->string('direccion', 255);
            $table->string('departamento', 60);
            $table->boolean('es_recomendada')->default(false);
            $table->boolean('es_prioritaria')->default(false);
            $table->integer('capacidad_por_hora')->default(20);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};
