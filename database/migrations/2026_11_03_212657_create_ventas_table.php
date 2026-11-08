<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id(); // PK - Número de operación / factura
            $table->foreignId('cliente_id')->constrained('users')->onDelete('restrict'); // Quién paga
            $table->foreignId('vendedor_id')->constrained('users')->onDelete('restrict'); // Qué boletero la hizo
            $table->dateTime('fecha_venta');
            $table->decimal('total', 10, 2); // Suma totalizada de los boletos de esta transacción
            $table->string('metodo_pago', 30)->default('efectivo'); // efectivo, tarjeta, transferencia
            $table->string('estado', 20)->default('completada'); // completada, anulada
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
