<?php
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Ruta 1: Genera y guarda el código desde el navegador
Route::get('/test-enviar', function (Request $request) {
    // Simulamos la petición pasando el email por la URL
    $req = new Request(['email' => 'mi_correo@test.com']);
    
    $controller = new VerificationController();
    return $controller->sendCode($req);
});

// Ruta 2: Probar el código escribiéndolo en la URL
Route::get('/test-verificar/{codigo}', function ($codigo) {
    $req = new Request([
        'email' => 'mi_correo@test.com',
        'code'  => $codigo
    ]);
    
    $controller = new VerificationController();
    return $controller->verifyCode($req);
});



Route::get('/', [AuthController::class, 'showPage'])->name('home');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/verification/send', [VerificationController::class, 'sendCode']);
Route::post('/verification/verify', [VerificationController::class, 'verifyCode']);

