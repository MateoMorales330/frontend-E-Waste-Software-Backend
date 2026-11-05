<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de empleado</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 30px; }
        .card { background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        a { display: inline-block; margin-top: 10px; background: #dc2626; color: white; text-decoration: none; padding: 10px 14px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Panel de empleado</h1>
        <p>Bienvenido, {{ Auth::user()->name }}.</p>
        <p>Tu acceso está limitado a ver información básica.</p>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:#dc2626; color:white; border:none; padding:10px 14px; border-radius:8px; cursor:pointer;">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>
