<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origen_id')->constrained('localidades')->onDelete('restrict');
            $table->foreignId('destino_id')->constrained('localidades')->onDelete('restrict');
            $table->decimal('distancia', 8, 2); // En kilómetros
            $table->time('duracion_estimada');
            $table->string('estado', 20)->default('activa'); // activa, inactiva
            $table->timestamps();

            // Evita duplicar la combinación exacta de trayecto
            $table->unique(['origen_id', 'destino_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('rutas');
    }
};

