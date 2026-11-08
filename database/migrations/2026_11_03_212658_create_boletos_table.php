<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
Schema::create('boletos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade'); // Conexión a la cabecera
    $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // Pasajero real que viaja
    $table->foreignId('viaje_id')->constrained('viajes')->onDelete('restrict');
    $table->foreignId('asiento_id')->constrained('asientos')->onDelete('restrict');
    $table->decimal('precio', 10, 2); // Precio individual del boleto
    $table->string('estado', 20)->default('vendido'); // vendido, cancelado
    $table->timestamps();

    // Regla de oro del PDF
    $table->unique(['viaje_id', 'asiento_id']); 
});

}

 
    

    public function down(): void {
        Schema::dropIfExists('boletos');
    }
};
