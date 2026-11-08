<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade');
            $table->string('nombre', 60);
            $table->unsignedSmallInteger('capacidad_asientos'); // Atributo base para la automatización
            $table->timestamps();

            // Evita registrar dos veces el mismo modelo bajo la misma marca
            $table->unique(['marca_id', 'nombre']); 
        });
    }

    public function down(): void {
        Schema::dropIfExists('modelos');
    }
};
