<?php

namespace Tests\Feature;

use App\Models\Categorias;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriaDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_borrar_una_categoria(): void
    {
        $categoria = Categorias::create([
            'nombre' => 'Celulares',
            'descripcion' => 'Categoria de prueba'
        ]);

        $response = $this->deleteJson('/api/categorias/' . $categoria->id);

        $response->assertOk();
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }
}
