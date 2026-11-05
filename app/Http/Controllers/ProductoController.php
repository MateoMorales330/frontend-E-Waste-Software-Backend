<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Detalle_Ventas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Producto::with('categorias')->latest()->get());
    }

    /**
     * Return products ordered by units sold.
     */
    public function masVendidos()
    {
        $ids = Detalle_Ventas::query()
            ->join('ventas', 'ventas.id', '=', 'detalle__ventas.venta_id')
            ->select('detalle__ventas.producto_id', DB::raw('SUM(detalle__ventas.cantidad) as unidades_vendidas'))
            ->groupBy('detalle__ventas.producto_id')
            ->orderByDesc('unidades_vendidas')
            ->limit(9)
            ->pluck('producto_id')
            ->all();

        if (!$ids) {
            return response()->json([]);
        }

        $productos = Producto::with('categorias')
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn ($producto) => array_search($producto->id, $ids))
            ->values();

        return response()->json($productos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        // AÑADIDO: la categoría se guarda en la tabla pivote, no en productos.
        $datos = $request->validated();
        $categoriaId = $datos['categoria_id'];
        unset($datos['categoria_id'], $datos['imagen']);

        // AÑADIDO: si vino un archivo de imagen, lo guardamos en el
        // disco público (storage/app/public/productos/...) y
        // guardamos solo la ruta relativa en la base de datos.
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($datos);
        $producto->categorias()->sync([$categoriaId]);

        return response()->json($producto->load('categorias'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return response()->json($producto->load('categorias'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $datos = $request->validated();
        $categoriaId = $datos['categoria_id'] ?? null;
        unset($datos['categoria_id'], $datos['imagen'], $datos['quitar_imagen']);

        // AÑADIDO: si mandaron una imagen nueva, reemplaza a la vieja
        // (borrando el archivo anterior para no dejar basura en el
        // disco). Si en cambio pidieron "quitar_imagen" y no mandaron
        // una nueva, borra la que tenía y la deja sin imagen.
        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        } elseif ($request->boolean('quitar_imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $datos['imagen'] = null;
        }

        $producto->update($datos);
        if ($categoriaId !== null) {
            $producto->categorias()->sync([$categoriaId]);
        }

        return response()->json($producto->fresh()->load('categorias'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        // AÑADIDO: borra el archivo de imagen del disco junto con el producto.
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();

        return response()->json(['message' => 'Eliminado']);
    }
}
