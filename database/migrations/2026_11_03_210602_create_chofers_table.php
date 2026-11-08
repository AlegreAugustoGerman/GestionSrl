<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('choferes', function (Blueprint $table) {
            $table->id();
            // Relación 1 a 1 estricta (Herencia)
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('licencia', 30)->unique();
            $table->string('estado', 20)->default('activo'); // activo, inactivo, suspendido
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('choferes');
    }
};
