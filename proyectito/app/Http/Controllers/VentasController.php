<?php

namespace App\Http\Controllers;

use App\Models\Ventas;
use App\Http\Requests\StoreVentasRequest;
use App\Http\Requests\UpdateVentasRequest;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ventas = Ventas::with(['user', 'detalles.producto'])
            ->latest('fecha')
            ->get();

        return response()->json($ventas->map(function (Ventas $venta) {
            return [
                'id' => $venta->id,
                'cliente' => $venta->user?->name ?? 'Sin datos',
                'fecha' => $venta->fecha?->format('Y-m-d'),
                'total' => (float) $venta->total,
                'estado' => 'completado',
                'items' => $venta->detalles->map(fn ($detalle) => [
                    'nombre' => $detalle->producto?->nombre ?? 'Producto',
                    'cantidad' => (int) $detalle->cantidad,
                ])->values(),
            ];
        })->values());
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
    public function store(StoreVentasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ventas $ventas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ventas $ventas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVentasRequest $request, Ventas $ventas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ventas $ventas)
    {
        //
    }
}
