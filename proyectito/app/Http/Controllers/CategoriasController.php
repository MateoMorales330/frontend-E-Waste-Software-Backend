<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Http\Requests\StoreCategoriasRequest;
use App\Http\Requests\UpdateCategoriasRequest;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Devuelve todas las categorías ordenadas para que el frontend las
        // pueda mostrar en el selector del admin y en el menú de navegación.
        return response()->json(Categorias::orderBy('nombre')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['mensaje' => 'Usa POST /api/categorias para crear una categoría.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriasRequest $request)
    {
        // Crea una categoría nueva desde el panel admin.
        // La validación del request asegura que el nombre sea correcto.
        $categoria = Categorias::create($request->validated());

        return response()->json($categoria, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorias $categorias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorias $categorias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriasRequest $request, Categorias $categorias)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorias $categorias)
    {
        // Si la categoría ya está ligada a productos, se bloquea la eliminación
        // para evitar romper datos del inventario / productos asociados.
        if ($categorias->productos()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar una categoría que tiene productos asociados.'
            ], 409);
        }

        // Borrado real de la categoría en la base de datos.
        $categorias->delete();

        return response()->json(['mensaje' => 'Categoría eliminada correctamente.']);
    }
}
