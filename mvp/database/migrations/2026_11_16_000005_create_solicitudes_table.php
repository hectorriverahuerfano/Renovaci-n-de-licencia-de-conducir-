<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_solicitud', 32)->unique()->index(); // GOES-RN-XXXXXXXX
            $table->foreignId('ciudadano_id')->constrained('ciudadanos_demo')->cascadeOnDelete();
            $table->foreignId('licencia_id')->nullable()->constrained('licencias')->nullOnDelete();
            $table->string('clase_tramite', 32)->default('PARTICULAR');
            $table->string('estado', 30)->default('INICIADA'); // INICIADA, DATOS_CONFIRMADOS, PAGADA, CITA_AGENDADA, COMPLETADA, CANCELADA
            $table->decimal('monto_tramite', 8, 2)->default(52.82);
            $table->decimal('monto_examen_visual', 8, 2)->default(3.39);
            $table->decimal('monto_mora', 8, 2)->default(0.00); // 5.71 si extemporánea
            $table->decimal('monto_total', 8, 2)->default(56.21);
            $table->string('correo_contacto', 150)->nullable();
            $table->string('telefono_contacto', 30)->nullable();
            $table->unsignedTinyInteger('paso_actual')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
