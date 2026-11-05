<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar empleado</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 30px; }
        form { background: white; padding: 20px; border-radius: 12px; max-width: 500px; }
        input { display: block; width: 100%; margin-bottom: 10px; padding: 8px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 14px; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>Agregar empleado</h1>
    <form method="POST" action="{{ route('empleados.store') }}">
        @csrf
        <label for="usuario_id">Selecciona un usuario</label>
        <select name="usuario_id" id="usuario_id" required>
            <option value="">-- Elige un usuario --</option>
            @foreach($usuarios as $usuario)
                <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
            @endforeach
        </select>
        <input type="number" step="0.01" name="salario" placeholder="Salario" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <input type="date" name="fecha_contratacion" required>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
