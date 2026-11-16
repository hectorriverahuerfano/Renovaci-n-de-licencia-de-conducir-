<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciudadano_id')->nullable()->constrained('ciudadanos_demo')->nullOnDelete();
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->nullOnDelete();
            $table->string('canal', 30); // SMS, EMAIL, WEB_ASISTIDA, TELEFONO
            $table->string('tipo', 50); // APELACION_ESQUELA, ATENCION_ASISTIDA, CONFIRMACION_CITA, RECORDATORIO
            $table->string('destinatario', 150);
            $table->text('mensaje');
            $table->string('estado', 20)->default('REGISTRADO'); // REGISTRADO, ENVIADO, RESUELTO
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
