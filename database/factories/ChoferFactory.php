<?php

namespace Database\Factories;
use App\Models\Chofer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChoferFactory extends Factory {
    public function definition(): array {
        return [
            // Crea automáticamente el registro en la tabla users para cumplir la herencia 1:1
            'user_id' => User::factory(), 
            'licencia' => 'N-' . fake()->unique()->numerify('########'),
            'estado' => 'activo',
        ];
    }
}
