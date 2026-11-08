<?php

namespace Database\Factories;

use App\Models\Localidad;
use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;

class RutaFactory extends Factory {
    public function definition(): array {
        return [
            'origen_id' => Localidad::factory(),
            'destino_id' => Localidad::factory(),
            'distancia' => fake()->randomFloat(2, 50, 400),
            'duracion_estimada' => fake()->time('H:i:s'),
            'estado' => 'activa',
        ];
    }
}
