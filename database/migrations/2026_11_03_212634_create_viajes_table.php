<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained('rutas')->onDelete('restrict');
            $table->foreignId('colectivo_id')->constrained('colectivos')->onDelete('restrict');
            $table->date('fecha');
            $table->time('hora_salida');
            $table->time('hora_llegada');
            $table->decimal('precio', 10, 2); // DECIMAL exacto para transacciones comerciales
            $table->string('estado', 20)->default('programado'); // programado, en curso, finalizado, cancelado
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('viajes');
    }
};

