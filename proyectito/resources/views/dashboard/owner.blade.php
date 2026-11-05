<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del dueño</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 30px; }
        .card { background: white; padding: 24px; border-radius: 12px; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        a { display: inline-block; margin-right: 10px; background: #2563eb; color: white; text-decoration: none; padding: 10px 14px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Panel del dueño</h1>
        <p>Bienvenido, {{ Auth::user()->name }}.</p>
        <p>Puedes gestionar productos y empleados.</p>
        <a href="{{ route('productos.index') }}">Gestionar productos</a>
        <a href="{{ route('empleados.index') }}">Gestionar empleados</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:#dc2626; color:white; border:none; padding:10px 14px; border-radius:8px; cursor:pointer;">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>
