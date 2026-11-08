<?php

namespace Database\Factories;
use App\Models\Localidad;
use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;
class LocalidadFactory extends Factory {
    public function definition(): array {
        return [
            'provincia_id' => Provincia::factory(),
            'nombre' => fake()->city()
        ];
    }
}
