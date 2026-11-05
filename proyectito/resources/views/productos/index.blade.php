<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 30px; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #e5e7eb; text-align: left; }
        .actions a { margin-right: 8px; color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Productos</h1>
    <p><a href="{{ route('productos.create') }}">Agregar producto</a></p>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio venta</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
        @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->precio_venta }}</td>
                <td>{{ $producto->stock }}</td>
                <td class="actions">
                    <a href="{{ route('productos.edit', $producto) }}">Editar</a>
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
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
