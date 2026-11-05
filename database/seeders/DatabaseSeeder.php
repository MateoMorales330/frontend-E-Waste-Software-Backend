<?php

namespace Database\Seeders;

use App\Models\Dueno;
use App\Models\User;
use App\Models\Categorias;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create several random users
        User::factory(4)->create();

        // Create a single test user with a fixed email, but avoid duplicates on re-seed
        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'telefono' => '000-000-0000',
            'direccion' => 'Test address',
        ]);

        Dueno::updateOrCreate([
            'email' => 'test@dueno.com',
        ], [
            'name' => 'Test Dueno',
            'password' => bcrypt('password'),
            'telefono' => '000-000-0000',
            'direccion' => 'Test address',
        ]);

        $this->command->info('Usuarios creados: ' . User::count());

        $categorias = Categorias::factory(4)->create();
        $this->command->info('Categorías creadas: ' . $categorias->count());

        Producto::factory(10)->create()->each(function ($producto) use ($categorias) {
            $producto->categorias()->attach($categorias->random(rand(1, 3))->pluck('id')->toArray());
        });

        $this->command->info('Productos creados: ' . Producto::count());
    }
}
