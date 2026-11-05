<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_register_and_manage_employees_and_products(): void
    {
        $this->post(route('register.post'), [
            'name' => 'Dueño Test',
            'email' => 'dueno@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'dueno@example.com',
            'role' => 'owner',
        ]);

        $employeeUser = User::factory()->create([
            'name' => 'Empleado Test',
            'email' => 'empleado@example.com',
            'role' => 'empleado',
        ]);

        $this->post(route('empleados.store'), [
            'usuario_id' => $employeeUser->id,
            'salario' => 1800.50,
            'telefono' => '555123456',
            'fecha_contratacion' => '2026-01-15',
        ])->assertRedirect(route('empleados.index'));

        $this->assertDatabaseHas('empleados', [
            'correo' => 'empleado@example.com',
            'telefono' => '555123456',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $employeeUser->id,
            'role' => 'empleado',
        ]);

        $this->post(route('productos.store'), [
            'nombre' => 'Laptop',
            'descripcion' => 'Equipo para oficina',
            'precio_compra' => 800.00,
            'precio_venta' => 1200.00,
            'stock' => 10,
            'stock_minimo' => 3,
            'codigo_barras' => 'ABC123456',
            'id_categoria' => 1,
            'id_proveedor' => 2,
        ])->assertRedirect(route('productos.index'));

        $this->assertDatabaseHas('productos', [
            'codigo_barras' => 'ABC123456',
            'nombre' => 'Laptop',
        ]);

        $producto = \App\Models\producto::where('codigo_barras', 'ABC123456')->firstOrFail();

        $this->put(route('productos.update', $producto), [
            'nombre' => 'Laptop Pro',
            'descripcion' => 'Equipo actualizado',
            'precio_compra' => 850.00,
            'precio_venta' => 1300.00,
            'stock' => 8,
            'stock_minimo' => 2,
            'codigo_barras' => 'ABC999999',
            'id_categoria' => 1,
            'id_proveedor' => 2,
        ])->assertRedirect(route('productos.index'));

        $this->assertDatabaseHas('productos', [
            'codigo_barras' => 'ABC999999',
            'nombre' => 'Laptop Pro',
        ]);

        $this->delete(route('productos.destroy', $producto->fresh()))->assertRedirect(route('productos.index'));

        $this->assertDatabaseMissing('productos', [
            'id_producto' => $producto->id_producto,
        ]);
    }
}
