# Backend de E-Waste Software
Somos E-Waste Software, un equipo de estudiantes de la UTU cursando nuestro proyecto final de Bachillerato en Informática. Desarrollamos una plataforma web orientada a optimizar la gestión comercial y la visibilidad digital de Zona Móbil, un local de accesorios para celulares, fusionando el comercio electrónico con prácticas sustentables. El código y el desarrollo de este proyecto se rigen bajo los términos de una licencia personalizada, detallada en el archivo Licencia.txt del repositorio.
API del sistema E-Waste Software, desarrollada con Laravel. La aplicación Laravel está dentro de `proyectito/`; ejecutá los comandos de Laravel desde esa carpeta.

## Requisitos

- PHP 8.3 o superior
- Composer
- SQLite

## Instalación local

Desde PowerShell, ubicándote en la carpeta `backend`:

```powershell
cd .\proyectito
composer install
Copy-Item .env.example .env
php artisan key:generate
if (-not (Test-Path database\database.sqlite)) { New-Item -ItemType File -Path database\database.sqlite | Out-Null }
php artisan migrate
```

Si ya existe un archivo `.env`, conservá tu configuración y no lo reemplaces con `.env.example`.

## Iniciar el servidor

Desde `backend\proyectito`:

```powershell
php artisan serve
```

Por defecto, Laravel queda disponible en `http://127.0.0.1:8000`. Las rutas de la API están bajo el prefijo `/api`; por ejemplo, `http://127.0.0.1:8000/api/productos`.

## Estructura

- `proyectito/app/`: controladores, modelos y lógica de la aplicación.
- `proyectito/routes/api.php`: endpoints de la API.
- `proyectito/database/migrations/`: esquema de la base de datos.
- `proyectito/database/seeders/`: datos de prueba.

El frontend se encuentra en un proyecto separado.
