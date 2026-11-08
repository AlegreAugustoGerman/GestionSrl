<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('viaje_chofers', function (Blueprint $table) {
            $table->id();
            // Aseguramos el borrado en cascada si se elimina un viaje o un chofer
            $table->foreignId('viaje_id')->constrained('viajes')->onDelete('cascade');
            $table->foreignId('chofer_id')->constrained('choferes')->onDelete('cascade');
            $table->timestamps();

            // Restricción 3FN: Impide asignar dos veces al mismo conductor al mismo viaje exacto
            $table->unique(['viaje_id', 'chofer_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('viaje_chofers');
    }
};
