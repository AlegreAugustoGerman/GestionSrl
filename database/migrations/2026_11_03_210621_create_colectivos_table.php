<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('colectivos', function (Blueprint $table) {
            $table->id();
            $table->string('patente', 10)->unique();
            $table->foreignId('modelo_id')->constrained('modelos')->onDelete('restrict');
            $table->unsignedSmallInteger('anio');
            $table->string('estado', 20)->default('activo'); // activo, mantenimiento, baja
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('colectivos');
    }
};

