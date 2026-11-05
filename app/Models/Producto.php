<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventario_Mov;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;
    protected $table = 'productos';
    protected $fillable = ['nombre', 'descripcion', 'precio_compra', 'precio_venta', 'stock', 'codigo_barras', 'estado', 'imagen'];

    // Se agrega "imagen_url" a la respuesta JSON automáticamente,
    // con la URL completa lista para usar en un <img>. Si el producto
    // no tiene imagen, queda en null.
    protected $appends = ['imagen_url'];

    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset('storage/' . $this->imagen) : null;
    }
    
    
    
    public function categorias()
    {
        return $this->belongsToMany(Categorias::class, 'productos_categorias', 'producto_id', 'categoria_id');
    }
    public function ventas()
    {
        return $this->belongsToMany(Ventas::class);
    }
    public function inventario_mov()
    {
        return $this->hasMany(Inventario_Mov::class);
    }
}
