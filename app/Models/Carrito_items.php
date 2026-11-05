<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito_items extends Model
{
    /** @use HasFactory<\Database\Factories\CarritoItemsFactory> */
    use HasFactory;
    protected $table = 'carrito_items';
    protected $fillable = ['carrito_id', 'producto_id', 'cantidad'];
    public function carrito(){
        return $this->belongsTo(Carrito::class);
    }
    public function producto(){
        return $this->belongsTo(Producto::class);
    }

}
