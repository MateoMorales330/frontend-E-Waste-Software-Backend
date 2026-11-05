<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    /** @use HasFactory<\Database\Factories\VentasFactory> */
    use HasFactory;
    protected $table = 'ventas';
    protected $fillable = ['user_id', 'total', 'fecha', 'metodo_pago'];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function detalles()
    {
        return $this->hasMany(Detalle_Ventas::class, 'venta_id');
    }
}
