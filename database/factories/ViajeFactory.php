<?php

namespace Database\Factories;

use App\Models\Colectivo;
use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViajeFactory extends Factory {
    public function definition(): array {
        return [
            'ruta_id' => Ruta::factory(),
            'colectivo_id' => Colectivo::factory(),
            'fecha' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'hora_salida' => '08:00:00',
            'hora_llegada' => '13:00:00',
            'precio' => fake()->randomFloat(2, 6000, 25000),
            'estado' => 'programado',
        ];
    }
}