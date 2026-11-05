<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('etiqueta')->nullable();
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->string('icono')->default('tag');
            $table->unsignedTinyInteger('color')->default(1);
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('archivado')->default(false);
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
