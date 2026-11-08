<?php

namespace Database\Factories;
use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\Factory;
class ModeloFactory extends Factory {
    public function definition(): array {
        return [
            'marca_id' => Marca::factory(),
            'nombre' => fake()->word() . ' ' . fake()->numericVal(100, 900),
            'capacidad_asientos' => fake()->randomElement([40, 44, 48]), // capacidades estándar
        ];
    }
}
