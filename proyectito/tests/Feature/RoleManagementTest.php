<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_a_product(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
        ]);

        $this->actingAs($owner);

        $response = $this->post('/productos', [
            'nombre' => 'Laptop',
            'descripcion' => 'Nueva laptop',
            'precio_compra' => 500,
            'precio_venta' => 700,
            'stock' => 10,
            'stock_minimo' => 2,
            'codigo_barras' => 'ABC123',
            'id_categoria' => 1,
            'id_proveedor' => 1,
        ]);

        $response->assertRedirect('/productos');
        $this->assertDatabaseHas('productos', ['nombre' => 'Laptop']);
    }

    public function test_employee_can_access_dashboard(): void
    {
        $employee = User::factory()->create([
            'role' => 'empleado',
        ]);

        $this->actingAs($employee);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Panel de empleado');
    }
}
