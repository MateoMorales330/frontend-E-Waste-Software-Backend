<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar producto</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 30px; }
        form { background: white; padding: 20px; border-radius: 12px; max-width: 500px; }
        input, textarea { display: block; width: 100%; margin-bottom: 10px; padding: 8px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 14px; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>Agregar producto</h1>
    <form method="POST" action="{{ route('productos.store') }}">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre" required>
        <textarea name="descripcion" placeholder="Descripción"></textarea>
        <input type="number" step="0.01" name="precio_compra" placeholder="Precio compra" required>
        <input type="number" step="0.01" name="precio_venta" placeholder="Precio venta" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="number" name="stock_minimo" placeholder="Stock mínimo" required>
        <input type="text" name="codigo_barras" placeholder="Código de barras" required>
        <input type="number" name="id_categoria" placeholder="ID categoría" required>
        <input type="number" name="id_proveedor" placeholder="ID proveedor" required>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
