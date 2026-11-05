<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega la columna "imagen" a productos: guarda la ruta relativa
     * del archivo dentro del disco público (ej: "productos/abc123.jpg").
     * La URL completa para mostrarla se arma sola en el modelo
     * (ver Producto::getImagenUrlAttribute), usando el disco "storage"
     * de Laravel — para que funcione hace falta correr una sola vez:
     *
     *   php artisan storage:link
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('codigo_barras');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }
};
