<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(
            [
                'success' => true,
                'data' => User::all(),
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'usuario',
        ]);
        $token = $user->createToken('frontend', ['role:usuario'])->plainTextToken;

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'success' => true,
            'data' => $user,
            'token' => $token,
        ], 201);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telefono' => ['nullable', 'string', 'max:30'],
        ]);

        $user->fill($data);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Datos de la cuenta actualizados correctamente.',
            'data' => $user->fresh(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente.',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            // 'tipo' => 'required|in:usuario,dueño',
        ]);

        // GHOST DEMO: autentica sin crear User ni PersonalAccessToken en la base.
        if (
            config('services.ghost_demo.enabled') &&
            hash_equals((string) config('services.ghost_demo.email'), $credentials['email']) &&
            hash_equals((string) config('services.ghost_demo.password'), $credentials['password'])
        ) {
            return response()->json([
                'success' => true,
                'ghost' => true,
                'data' => [
                    'id' => 0,
                    'name' => config('services.ghost_demo.name'),
                    'email' => config('services.ghost_demo.email'),
                    'role' => 'dueño',
                    'ghost' => true,
                ],
                'token' => config('services.ghost_demo.token'),
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Correo o contraseña incorrectos',
            ], 401);
        }

        $role = $user->role ?? 'usuario';
        $token = $user->createToken('frontend', ['role:' . $role])->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => $user,
            'token' => $token,
        ]);
    }

    public function empleadosIndex(Request $request)
    {
        $usuario = $request->user();
        if (!$usuario || !in_array($usuario->role, ['dueño', 'owner'], true)) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $empleados = User::where('role', 'empleado')
            ->orderBy('id')
            ->get()
            ->map(function (User $empleado) {
                return [
                    'id' => $empleado->id,
                    'nombre' => $empleado->name,
                    'puesto' => $empleado->puesto ?? 'Sin puesto',
                    'email' => $empleado->email,
                    'telefono' => $empleado->telefono,
                    'activo' => (bool) $empleado->activo,
                ];
            });

        return response()->json($empleados);
    }

    public function empleadosStore(Request $request)
    {
        $usuario = $request->user();
        if (!$usuario || !in_array($usuario->role, ['dueño', 'owner'], true)) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'puesto' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $contrasena = str()->random(12);
        $empleado = User::create([
            'name' => $datos['nombre'],
            'email' => $datos['email'],
            'password' => Hash::make($contrasena),
            'telefono' => $datos['telefono'] ?? null,
            'puesto' => $datos['puesto'],
            'activo' => $datos['activo'] ?? true,
            'role' => 'empleado',
        ]);

        return response()->json([
            'message' => 'Empleado agregado correctamente.',
            'data' => [
                'id' => $empleado->id,
                'nombre' => $empleado->name,
                'puesto' => $empleado->puesto,
                'email' => $empleado->email,
                'telefono' => $empleado->telefono,
                'activo' => (bool) $empleado->activo,
            ],
            'password_temporal' => $contrasena,
        ], 201);
    }

    public function empleadosUpdate(Request $request, User $user)
    {
        $usuario = $request->user();
        if (!$usuario || !in_array($usuario->role, ['dueño', 'owner'], true)) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        if (($user->role ?? null) !== 'empleado') {
            return response()->json(['message' => 'Empleado no encontrado.'], 404);
        }

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'puesto' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telefono' => ['nullable', 'string', 'max:30'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $user->update([
            'name' => $datos['nombre'],
            'email' => $datos['email'],
            'telefono' => $datos['telefono'] ?? null,
            'puesto' => $datos['puesto'],
            'activo' => $datos['activo'] ?? $user->activo,
        ]);

        return response()->json([
            'message' => 'Empleado actualizado correctamente.',
            'data' => [
                'id' => $user->id,
                'nombre' => $user->name,
                'puesto' => $user->puesto,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'activo' => (bool) $user->activo,
            ],
        ]);
    }

    public function empleadosDestroy(Request $request, User $user)
    {
        $usuario = $request->user();
        if (!$usuario || !in_array($usuario->role, ['dueño', 'owner'], true)) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        if (($user->role ?? null) !== 'empleado') {
            return response()->json(['message' => 'Empleado no encontrado.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Empleado eliminado.']);
    }
}