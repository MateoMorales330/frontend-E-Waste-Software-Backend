<?php

namespace Database\Seeders;

use App\Models\Dueno;
use App\Models\User;
use App\Models\Categorias;
use App\Models\Producto;
use App\Models\Ventas;
use App\Models\Carrito;
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
        // Cambia a true para crear categorías y productos de prueba.
        // En false se reutilizan las categorías y productos existentes.
        $generarCatalogo = false;

        // 1. Crear Usuarios
        User::factory(4)->create();

        $testUser = User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'telefono' => '000-000-0000',
            'direccion' => 'Test address',
        ]);

        // 2. Crear Dueño
        Dueno::updateOrCreate([
            'email' => 'test@dueno.com',
        ], [
            'name' => 'Test Dueno',
            'password' => bcrypt('password'),
            'telefono' => '000-000-0000',
            'direccion' => 'Test address',
        ]);

        $this->command->info('Usuarios creados: ' . User::count());

        // 3 y 4. Crear o reutilizar categorías y productos
        if ($generarCatalogo) {
            $categorias = Categorias::factory(4)->create();
            $productos = Producto::factory(10)->create();

            $productos->each(function ($producto) use ($categorias) {
                $producto->categorias()->attach($categorias->random(rand(1, 3))->pluck('id')->toArray());
            });

            $this->command->info('Categorías y productos de prueba creados.');
        } else {
            $categorias = Categorias::all();
            $productos = Producto::all();

            $this->command->info('Se reutilizan categorías y productos existentes.');
        }

        $this->command->info('Categorías disponibles: ' . $categorias->count());
        $this->command->info('Productos disponibles: ' . $productos->count());

        // ---------------------------------------------------------
        // NUEVO PASO: 4.5. Generar Carritos con productos aleatorios
        // ---------------------------------------------------------
        $users = User::all();
        
        foreach ($users as $user) {
            // Creamos un carrito por usuario
            $carrito = Carrito::create([
                'user_id' => $user->id,
            ]);

            // Elegimos entre 1 y 4 productos al azar de los que acabamos de crear
            $productosParaCarrito = $productos->random(rand(1, 4));

            foreach ($productosParaCarrito as $producto) {
                // Insertamos los productos en el carrito
                // Esto asume que tienes una relación hasMany llamada 'carrito_items' en el modelo Carrito
                $carrito->carrito_items()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => rand(1, 3), // cantidad aleatoria
                ]);
            }
        }
        
        $this->command->info('Carritos y sus ítems generados correctamente.');


        // 5. Crear ventas a partir de los pedidos existentes (carritos)
        $carritos = Carrito::with('carrito_items.producto')
            ->whereHas('carrito_items')
            ->get();

        if ($carritos->isEmpty()) {
            $this->command->warn('No se crearon ventas: no hay pedidos con artículos guardados en la base de datos.');
            return;
        }

        foreach ($carritos as $carrito) {
            $venta = Ventas::create([
                'user_id' => $carrito->user_id,
                'fecha' => $carrito->created_at?->toDateString() ?? now()->toDateString(),
                'metodo_pago' => 'pendiente',
                'total' => 0,
            ]);

            $totalVenta = 0;

            foreach ($carrito->carrito_items as $item) {
                $producto = $item->producto;

                if (!$producto) {
                    continue;
                }

                $cantidad = $item->cantidad;
                $precioUnitario = $producto->precio_venta;

                $venta->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio' => $precioUnitario,
                ]);

                // Este seeder desactiva los eventos de Eloquent; descontamos
                // el inventario explícitamente para que las ventas sembradas
                // también actualicen stock y estado.
                $productoActualizado = Producto::find($producto->id);
                $stockRestante = max(0, (int) $productoActualizado->stock - (int) $cantidad);
                $productoActualizado->update([
                    'stock' => $stockRestante,
                    'estado' => $stockRestante === 0 ? 'agotado' : 'disponible',
                ]);

                $totalVenta += $cantidad * $precioUnitario;
            }

            $venta->update(['total' => $totalVenta]);
        }

        $this->command->info('Ventas creadas desde pedidos: ' . $carritos->count());
    }
}