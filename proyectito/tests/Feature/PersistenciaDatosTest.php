<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersistenciaDatosTest extends TestCase
{
    use RefreshDatabase;

    public function test_crea_registro_de_dueno_empleado_y_producto(): void
    {
        $response = $this->post('/register', [
            'name' => 'Dueño Test',
            'email' => 'dueno@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'dueno@test.com',
            'role' => 'owner',
        ]);
        $this->assertDatabaseHas('duenos', [
            'correo' => 'dueno@test.com',
            'nombre' => 'Dueño Test',
        ]);

        $empleadoUser = User::factory()->create([
            'name' => 'Empleado Test',
            'email' => 'empleado@test.com',
            'role' => 'empleado',
        ]);

        $owner = User::where('email', 'dueno@test.com')->firstOrFail();
        $this->actingAs($owner);

        $employeeResponse = $this->post('/empleados', [
            'usuario_id' => $empleadoUser->id,
            'salario' => '1500.50',
            'telefono' => '999999999',
            'fecha_contratacion' => '2026-01-15',
        ]);

        $employeeResponse->assertRedirect('/empleados');
        $this->assertDatabaseHas('empleados', [
            'nombre' => 'Empleado Test',
            'correo' => 'empleado@test.com',
            'salario' => '1500.50',
            'telefono' => '999999999',
        ]);

        $productResponse = $this->post('/productos', [
            'nombre' => 'Producto Test',
            'descripcion' => 'Descripción de prueba',
            'precio_compra' => '100.00',
            'precio_venta' => '150.00',
            'stock' => '10',
            'stock_minimo' => '3',
            'codigo_barras' => 'ABC123',
            'id_categoria' => '1',
            'id_proveedor' => '1',
        ]);

        $productResponse->assertRedirect('/productos');
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Producto Test',
            'codigo_barras' => 'ABC123',
            'precio_venta' => '150.00',
        ]);
    }
}
