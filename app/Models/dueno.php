<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Dueno extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'duenos';
    protected $fillable = ['name', 'email', 'password', 'telefono', 'direccion'];
}
