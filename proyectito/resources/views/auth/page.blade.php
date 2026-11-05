<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al sistema</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 40px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 24px; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; }
        .btn { border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-primary { background: #2563eb; color: white; }
        form { display: flex; flex-direction: column; gap: 12px; }
        input { padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; }
        .error { color: #dc2626; font-size: 14px; margin: 0; }
        .success { color: #15803d; font-size: 14px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Acceso al sistema</h1>
        <p>Inicia sesión o crea una cuenta para entrar.</p>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif

        <div class="grid">
            <div class="card">
                <h2>Iniciar sesión</h2>
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <input type="email" name="email" placeholder="Correo" value="{{ old('email') }}" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <label><input type="checkbox" name="remember"> Recordarme</label>
                    <button class="btn btn-primary" type="submit">Ingresar</button>
                </form>
            </div>

            <div class="card">
                <h2>Registro</h2>
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Nombre" value="{{ old('name') }}" required>
                    <input type="email" name="email" placeholder="Correo" value="{{ old('email') }}" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
                    <button class="btn btn-primary" type="submit">Crear cuenta</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
