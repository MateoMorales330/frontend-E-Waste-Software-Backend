<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 30px; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #e5e7eb; text-align: left; }
        button { background: #dc2626; color: white; border: none; padding: 8px 10px; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>Empleados</h1>
    <p><a href="{{ route('empleados.create') }}">Agregar empleado</a></p>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
        @foreach($empleados as $empleado)
            <tr>
                <td>{{ $empleado->nombre }}</td>
                <td>{{ $empleado->correo }}</td>
                <td>{{ $empleado->telefono }}</td>
                <td>
                    <form action="{{ route('empleados.destroy', $empleado) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>
