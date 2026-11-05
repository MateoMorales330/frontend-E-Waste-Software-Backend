<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\dueno;
use App\Models\empleado;
use App\Models\producto;

$u = User::create(['name' => 'Prueba', 'email' => 'prueba3@example.com', 'password' => bcrypt('password'), 'role' => 'owner']);
dueno::create(['id_dueno' => $u->id, 'nombre' => $u->name, 'correo' => $u->email, 'password' => $u->password]);
$e = User::create(['name' => 'Emp', 'email' => 'emp3@example.com', 'password' => bcrypt('password'), 'role' => 'empleado']);
empleado::create(['id_usuario' => $e->id, 'nombre' => $e->name, 'correo' => $e->email, 'salario' => 1200, 'telefono' => '111', 'fecha_contratacion' => '2026-01-01']);
producto::create(['nombre' => 'Prod', 'descripcion' => 'desc', 'precio_compra' => 10, 'precio_venta' => 15, 'stock' => 5, 'stock_minimo' => 2, 'codigo_barras' => 'ABC1000', 'id_categoria' => 1, 'id_proveedor' => 1]);

echo 'dueno=' . dueno::where('correo', 'prueba3@example.com')->count() . ', empleado=' . empleado::where('correo', 'emp3@example.com')->count() . ', producto=' . producto::where('codigo_barras', 'ABC1000')->count();
