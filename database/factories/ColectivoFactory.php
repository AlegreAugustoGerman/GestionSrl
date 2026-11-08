<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Viaje;
use App\Models\Asiento;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoletoFactory extends Factory {
    public function definition(): array {
        return [
            'user_id' => User::factory(),
            'viaje_id' => Viaje::factory(),
            // El asiento idealmente se debe amarrar al colectivo del viaje en el Seeder
            'asiento_id' => Asiento::factory(), 
            'fecha_compra' => now(),
            'precio' => fake()->randomFloat(2, 5000, 25000),
            'estado' => 'vendido',
        ];
    }
}
