<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'etiqueta',
        'titulo',
        'subtitulo',
        'icono',
        'color',
        'activo',
        'orden',
        'archivado',
        'imagen',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'archivado' => 'boolean',
        'color' => 'integer',
        'orden' => 'integer',
    ];

    protected $appends = ['imagen_url'];

    public function getImagenUrlAttribute(): ?string
    {
        return $this->imagen ? url('/api/banners/' . $this->id . '/imagen') : null;
    }
}
