<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_Ventas extends Model
{
    /** @use HasFactory<\Database\Factories\DetalleVentasFactory> */
    use HasFactory;protected $table = 'detalle_ventas';
    protected $fillable = ['venta_id', 'producto_id', 'cantidad', 'precio_unitario'];
    public function venta()
    {
        return $this->belongsTo(Ventas::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

}
