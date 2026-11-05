<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Detalle_Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $archivados = $request->boolean('archivados');

        return response()->json(
            Producto::with('categorias')
                ->where('archivado', $archivados)
                ->latest()
                ->get()
        );
    }

    /**
     * Return non-archived products ordered by units sold.
     */
    public function masVendidos()
    {
        $ids = Detalle_Ventas::query()
            ->join('ventas', 'ventas.id', '=', 'detalle__ventas.venta_id')
            ->join('productos', 'productos.id', '=', 'detalle__ventas.producto_id')
            ->where('productos.archivado', false)
            ->where('productos.stock', '>', 0)
            ->where('productos.estado', '!=', 'agotado')
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
            ->where('archivado', false)
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
        if (array_key_exists('stock', $datos)) {
            $datos['estado'] = (int) $datos['stock'] > 0 ? 'disponible' : 'agotado';
        }
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

    public function imagen(Producto $producto)
    {
        abort_unless($producto->imagen, 404);

        $ruta = storage_path('app/public/' . $producto->imagen);
        abort_unless(is_file($ruta), 404);

        return response()->file($ruta);
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
        if (array_key_exists('stock', $datos)) {
            $datos['estado'] = (int) $datos['stock'] > 0 ? 'disponible' : 'agotado';
        }
        if ($request->hasFile('imagen')) {
            if ($producto->imagen) Storage::disk('public')->delete($producto->imagen);
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        } elseif ($request->boolean('quitar_imagen')) {
            if ($producto->imagen) Storage::disk('public')->delete($producto->imagen);
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
        if ($producto->imagen) Storage::disk('public')->delete($producto->imagen);
        $producto->delete();

        return response()->json(['message' => 'Eliminado']);
    }
}
