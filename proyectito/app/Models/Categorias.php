<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriasFactory> */
    use HasFactory;
    protected $table = 'categorias';
    protected $fillable = ['nombre', 'descripcion'];
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'productos_categorias', 'categoria_id', 'producto_id');
    }
}