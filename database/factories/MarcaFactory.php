<?php

namespace Database\Factories;
use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\Factory;

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class MarcaFactory extends Factory {
    public function definition(): array {
        return ['nombre' => fake()->unique()->company()];
    }
}
