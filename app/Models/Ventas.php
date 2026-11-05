<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    /** @use HasFactory<\Database\Factories\VentasFactory> */
    use HasFactory;
    protected $table = 'ventas';
    protected $fillable = ['user_id', 'producto_id', 'cantidad', 'precio_total', 'fecha', 'metodo_pago'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function detalles()
    {
        return $this->hasMany(Detalle_Ventas::class);
    }
}
