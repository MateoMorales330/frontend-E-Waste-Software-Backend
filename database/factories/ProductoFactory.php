<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word(3, true),
            'descripcion' => $this->faker->sentence(),
            'precio_compra' => $this->faker->randomFloat(2, 10, 100),
            'precio_venta' => $this->faker->randomFloat(2, 101, 200),
            'stock' => $this->faker->numberBetween(0, 100),
            'codigo_barras' => $this->faker->ean13(),
            'estado' => $this->faker->randomElement(['disponible', 'agotado']),
        ];
    }
}
