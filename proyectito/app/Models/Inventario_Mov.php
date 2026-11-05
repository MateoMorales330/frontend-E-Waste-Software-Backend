<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario_Mov extends Model
{
    /** @use HasFactory<\Database\Factories\InventarioMovFactory> */
    use HasFactory;
    protected $table = 'inventario_mov';
    protected $fillable = ['producto_id', 'cantidad', 'tipo_movimiento', 'fecha'];
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
