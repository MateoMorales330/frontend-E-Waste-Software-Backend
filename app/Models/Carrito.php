<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    /** @use HasFactory<\Database\Factories\CarritoFactory> */
    use HasFactory;
    protected $table = 'carritos';
    protected $fillable = ['user_id', 'session_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function carrito_items()
    {
        return $this->hasMany(Carrito_Items::class);
    }
}
