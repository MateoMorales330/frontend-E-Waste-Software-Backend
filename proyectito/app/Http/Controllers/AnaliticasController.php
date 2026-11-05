<?php

namespace App\Http\Controllers;

use App\Models\Detalle_Ventas;
use App\Models\Producto;
use App\Models\User;
use App\Models\Ventas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnaliticasController extends Controller
{
    public function index()
    {
        // Tomamos como referencia la venta más reciente para que los datos
        // sembrados o importados también aparezcan en el gráfico.
        $ultimaFechaVenta = Ventas::max('fecha');
        $hoy = $ultimaFechaVenta ? Carbon::parse($ultimaFechaVenta) : Carbon::today();
        $inicio = $hoy->copy()->subDays(7);
        $ventasPeriodo = Ventas::whereBetween('fecha', [$inicio->toDateString(), $hoy->toDateString()]);
        $cantidadOrdenes = (clone $ventasPeriodo)->count();
        $ventasTotales = (float) (clone $ventasPeriodo)->sum('total');

        // Agrupamos las ventas por fecha para alimentar las dos series del gráfico.
        $ventasPorDia = (clone $ventasPeriodo)
            ->select('fecha', DB::raw('SUM(total) as ventas'), DB::raw('COUNT(*) as ordenes'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->keyBy(fn ($venta) => Carbon::parse($venta->fecha)->format('Y-m-d'));

        // Incluimos también los días sin ventas para que el gráfico no tenga saltos.
        $grafico = collect(range(7, 0))->map(function ($diasAtras) use ($hoy, $ventasPorDia) {
            $fecha = $hoy->copy()->subDays($diasAtras);
            $venta = $ventasPorDia->get($fecha->format('Y-m-d'));

            return [
                'etiqueta' => $fecha->format('d M'),
                'ventas' => (float) ($venta->ventas ?? 0),
                'ordenes' => (int) ($venta->ordenes ?? 0),
            ];
        })->values();

        // Los productos más vendidos se calculan con todo el historial,
        // no solo con los últimos 7 días del gráfico.
        $detallesPeriodo = Detalle_Ventas::query();

        // Calculamos los productos más vendidos usando cantidad e importe real.
        $productos = (clone $detallesPeriodo)
            ->join('productos', 'productos.id', '=', 'detalle__ventas.producto_id')
            ->select(
                'productos.id',
                'productos.nombre',
                DB::raw('SUM(detalle__ventas.cantidad) as unidades'),
                DB::raw('SUM(detalle__ventas.cantidad * detalle__ventas.precio) as ventas')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('unidades')
            ->limit(5)
            ->get()
            ->map(fn ($producto) => [
                'nombre' => $producto->nombre,
                'unidades' => (int) $producto->unidades,
                'ventas' => (float) $producto->ventas,
            ])
            ->values();

        // Las categorías se obtienen desde la tabla pivote productos_categorias.
        $categorias = (clone $detallesPeriodo)
            ->join('productos_categorias', 'productos_categorias.producto_id', '=', 'detalle__ventas.producto_id')
            ->join('categorias', 'categorias.id', '=', 'productos_categorias.categoria_id')
            ->select('categorias.id', 'categorias.nombre', DB::raw('SUM(detalle__ventas.cantidad * detalle__ventas.precio) as ventas'))
            ->groupBy('categorias.id', 'categorias.nombre')
            ->orderByDesc('ventas')
            ->get();
        $ventasCategorias = (float) $categorias->sum('ventas');
        $productosDisponibles = Producto::where('stock', '>', 0)->count();
        $productosAgotados = Producto::where('stock', '<=', 0)->count();
        $stockTotal = (int) Producto::sum('stock');

        // La respuesta mantiene una estructura simple para que Vue pueda consumirla directamente.
        return response()->json([
            'periodo' => [
                'inicio' => $inicio->toDateString(),
                'fin' => $hoy->toDateString(),
            ],
            'resumen' => [
                'ventas_totales' => $ventasTotales,
                'ordenes' => $cantidadOrdenes,
                'clientes_nuevos' => User::whereBetween('created_at', [$inicio->copy()->startOfDay(), $hoy->copy()->endOfDay()])->count(),
                'ticket_promedio' => $cantidadOrdenes ? round($ventasTotales / $cantidadOrdenes, 2) : 0,
                'productos_disponibles' => $productosDisponibles,
                'stock_total' => $stockTotal,
                'productos_agotados' => $productosAgotados,
            ],
            'grafico' => $grafico,
            'categorias' => $categorias->map(fn ($categoria) => [
                'etiqueta' => $categoria->nombre,
                'ventas' => (float) $categoria->ventas,
                'porcentaje' => $ventasCategorias ? round(((float) $categoria->ventas / $ventasCategorias) * 100, 1) : 0,
            ])->values(),
            'productos' => $productos,
        ]);
    }
}
