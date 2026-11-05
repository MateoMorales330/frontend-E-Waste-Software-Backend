<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DuenoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentasController;
// AÑADIDO: controlador para consultar las categorías del formulario.
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\AnaliticasController;
use App\Http\Controllers\BannerController;


Route::post('/verification/send', [VerificationController::class, 'sendCode']);
Route::post('/verification/verify', [VerificationController::class, 'verifyCode']);


// Ruta para obtener y editar datos del usuario logueado.
// Acepta tanto el token real de Sanctum como el token fantasma del demo.
Route::middleware('ghost.auth')->group(function () {
  Route::get('/user', function (Request $request) {
    return $request->user();
  });

  Route::put('/user', [UserController::class, 'updateProfile']);
  Route::put('/user/password', [UserController::class, 'updatePassword']);

  Route::get('/empleados', [UserController::class, 'empleadosIndex']);
  Route::post('/empleados', [UserController::class, 'empleadosStore']);
  Route::put('/empleados/{user}', [UserController::class, 'empleadosUpdate']);
  Route::delete('/empleados/{user}', [UserController::class, 'empleadosDestroy']);

  // Ruta para cerrar sesión (logout)
  Route::post('/logout', function (Request $request) {
    $user = $request->user();
    if (method_exists($user, 'tokens')) {
      $user->tokens()->delete();
    }
    return response()->json(['mensaje' => 'Sesión cerrada correctamente']);
  });
});

Route::middleware(['ghost.auth', 'owner.only'])->group(function () {
  Route::post('/categorias', [CategoriasController::class, 'store']);
  Route::delete('/categorias/{categorias}', [CategoriasController::class, 'destroy']);
  Route::apiResource('productos', ProductoController::class)->except(['index', 'show']);
  Route::get('/admin/banners', [BannerController::class, 'index']);
  Route::post('/banners', [BannerController::class, 'store']);
  Route::match(['put', 'patch', 'post'], '/banners/{banner}', [BannerController::class, 'update']);
  Route::delete('/banners/{banner}', [BannerController::class, 'destroy']);
  Route::get('/analiticas', [AnaliticasController::class, 'index']);
  Route::get('/ordenes', [VentasController::class, 'index']);
});
Route::get('/productos/{producto}/imagen', [ProductoController::class, 'imagen']);
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{producto}', [ProductoController::class, 'show']);
Route::get('/mas-vendidos', [ProductoController::class, 'masVendidos']);
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/banners/{banner}/imagen', [BannerController::class, 'imagen']);
// Endpoints de categorías para el admin:
// - GET /api/categorias: listar
// - POST /api/categorias: crear
// - DELETE /api/categorias/{id}: borrar
Route::get('/categorias', [CategoriasController::class, 'index']);

Route::post('/usuarios/registro', [UserController::class, 'store']);
Route::post('/usuarios/login', [UserController::class, 'login']);