<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colectivo_id')->constrained('colectivos')->onDelete('cascade');
            $table->unsignedSmallInteger('numero');
            $table->string('tipo', 20)->default('semicama'); // comun, semicama, cama
            $table->string('estado', 20)->default('habilitado'); // habilitado, inhabilitado
            $table->timestamps();

            // Evita duplicar el número de asiento en el mismo colectivo
            $table->unique(['colectivo_id', 'numero']); 
        });
    }

    public function down(): void {
        Schema::dropIfExists('asientos');
    }
};
