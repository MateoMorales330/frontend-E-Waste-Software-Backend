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
    protected $fillable = ['nombre', 'descripcion', 'precio_compra', 'precio_venta', 'stock', 'codigo_barras', 'estado', 'imagen', 'archivado'];

    protected $casts = [
        'archivado' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $producto): void {
            $producto->estado = (int) $producto->stock > 0 ? 'disponible' : 'agotado';
        });
    }

    protected $appends = ['imagen_url'];

    public function getImagenUrlAttribute()
    {
        return $this->imagen ? url('/api/productos/' . $this->id . '/imagen') : null;
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
