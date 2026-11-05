<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $archivados = $request->boolean('archivados');
        $admin = $request->is('api/admin/banners');

        $query = Banner::query()->where('archivado', $archivados);
        if (!$admin) {
            $query->where('activo', true);
        }

        return response()->json($query->orderBy('orden')->orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $datos['orden'] = (int) ($datos['orden'] ?? ((int) Banner::max('orden') + 1));
        $datos['activo'] = $request->boolean('activo');
        $datos['archivado'] = false;

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('banners', 'public');
        }

        return response()->json(Banner::create($datos), 201);
    }

    public function update(Request $request, Banner $banner)
    {
        $datos = $this->validar($request, false);
        unset($datos['imagen'], $datos['quitar_imagen']);

        if ($request->has('activo')) {
            $datos['activo'] = $request->boolean('activo');
        }
        if ($request->has('archivado')) {
            $datos['archivado'] = $request->boolean('archivado');
            if ($datos['archivado']) {
                $datos['activo'] = false;
            }
        }
        if ($request->hasFile('imagen')) {
            if ($banner->imagen) Storage::disk('public')->delete($banner->imagen);
            $datos['imagen'] = $request->file('imagen')->store('banners', 'public');
        } elseif ($request->boolean('quitar_imagen')) {
            if ($banner->imagen) Storage::disk('public')->delete($banner->imagen);
            $datos['imagen'] = null;
        }

        $banner->update($datos);
        return response()->json($banner->fresh());
    }

    public function destroy(Banner $banner)
    {
        if ($banner->imagen) Storage::disk('public')->delete($banner->imagen);
        $banner->delete();
        return response()->json(['message' => 'Banner eliminado']);
    }

    public function imagen(Banner $banner)
    {
        abort_unless($banner->imagen, 404);
        $ruta = storage_path('app/public/' . $banner->imagen);
        abort_unless(is_file($ruta), 404);
        return response()->file($ruta);
    }

    private function validar(Request $request, bool $crear = true): array
    {
        return $request->validate([
            'etiqueta' => [$crear ? 'nullable' : 'sometimes', 'string', 'max:255'],
            'titulo' => [$crear ? 'required' : 'sometimes', 'string', 'max:255'],
            'subtitulo' => ['nullable', 'string', 'max:255'],
            'icono' => ['sometimes', 'string', 'max:50'],
            'color' => ['sometimes', 'integer', 'between:1,4'],
            'activo' => ['sometimes', 'boolean'],
            'orden' => ['sometimes', 'integer', 'min:0'],
            'archivado' => ['sometimes', 'boolean'],
            'imagen' => ['nullable', 'image', 'max:2048'],
            'quitar_imagen' => ['sometimes', 'boolean'],
        ]);
    }
}
