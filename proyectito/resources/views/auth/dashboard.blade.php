<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 40px; }
        .card { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); }
        .btn { display: inline-block; margin-top: 16px; background: #dc2626; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Sesión iniciada correctamente</h1>
        <p>Bienvenido, {{ Auth::user()->name }}.</p>
        <p>La aplicación quedó reducida a login y registro.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn" type="submit">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>
