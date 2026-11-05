<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DuenoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ProductoController;
// AÑADIDO: controlador para consultar las categorías del formulario.
use App\Http\Controllers\CategoriasController;


// Ruta para obtener y editar datos del usuario logueado
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', function (Request $request) {
    return $request->user();
  });

  Route::put('/user', [UserController::class, 'updateProfile']);
  Route::put('/user/password', [UserController::class, 'updatePassword']);

  // Ruta para cerrar sesión (logout)
  Route::post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['mensaje' => 'Sesión cerrada correctamente']);
  });
});

Route::middleware('auth:sanctum', 'ability:role:dueño')->group(function ()

{ Route::post('/categorias', [CategoriasController::class, 'store']);
  Route::delete('/categorias/{categorias}', [CategoriasController::class, 'destroy']);
  Route::apiResource('productos', ProductoController::class)->except(['index', 'show']);
});
Route::apiResource('productos', ProductoController::class);
Route::get('/mas-vendidos', [ProductoController::class, 'masVendidos']);
// Endpoints de categorías para el admin:
// - GET /api/categorias: listar
// - POST /api/categorias: crear
// - DELETE /api/categorias/{id}: borrar
Route::get('/categorias', [CategoriasController::class, 'index']);
Route::post('/categorias', [CategoriasController::class, 'store']);
Route::delete('/categorias/{categorias}', [CategoriasController::class, 'destroy']);

Route::post('/usuarios/registro', [UserController::class, 'store']);
Route::post('/usuarios/login', [UserController::class, 'login']);