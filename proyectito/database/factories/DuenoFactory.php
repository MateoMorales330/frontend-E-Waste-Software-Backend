<?php

namespace Database\Factories;

use App\Models\Dueno;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dueno>
 */
class DuenoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'password' => bcrypt('password'), // Contraseña por defecto
        ];
    }
}
