<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_Ventas extends Model
{
    /** @use HasFactory<\Database\Factories\DetalleVentasFactory> */
    use HasFactory;

    protected $table = 'detalle__ventas';
    protected $fillable = ['venta_id', 'producto_id', 'cantidad', 'precio'];

    protected static function booted(): void
    {
        static::created(function (self $detalle): void {
            $producto = Producto::find($detalle->producto_id);

            if (!$producto) {
                return;
            }

            $stockRestante = max(0, (int) $producto->stock - (int) $detalle->cantidad);

            $producto->update([
                'stock' => $stockRestante,
                'estado' => $stockRestante === 0 ? 'agotado' : 'disponible',
            ]);
        });
    }

    public function venta()
    {
        return $this->belongsTo(Ventas::class, 'venta_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

}
