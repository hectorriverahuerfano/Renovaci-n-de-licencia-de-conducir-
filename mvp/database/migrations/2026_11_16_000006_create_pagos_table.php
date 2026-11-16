<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->string('referencia_transaccion', 64)->unique()->index();
            $table->string('metodo_pago', 40); // TARJETA, CHIVO_WALLET, TRANSFERENCIA_365, MANDAMIENTO_NPE
            $table->decimal('monto', 8, 2);
            $table->string('estado', 20)->default('PENDIENTE'); // PENDIENTE, APROBADO, RECHAZADO
            $table->string('npe', 40)->nullable()->index(); // 30 dígitos para pago en ventanilla/bancos
            $table->date('npe_vencimiento')->nullable();
            $table->json('detalles_pasarela')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
