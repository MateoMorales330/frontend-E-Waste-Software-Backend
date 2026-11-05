<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateGhostOrSanctum
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $ghostToken = (string) config('services.ghost_demo.token');

        // GHOST DEMO: identidad temporal, no crea filas en users ni personal_access_tokens.
        if (config('services.ghost_demo.enabled') && $ghostToken !== '' && $token !== '' && hash_equals($ghostToken, $token)) {
            $ghostUser = new GenericUser([
                'id' => 0,
                'name' => config('services.ghost_demo.name'),
                'email' => config('services.ghost_demo.email'),
                'role' => 'dueño',
            ]);

            Auth::guard('sanctum')->setUser($ghostUser);
            $request->setUserResolver(fn () => $ghostUser);

            return $next($request);
        }

        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $request->setUserResolver(fn () => $user);
        return $next($request);
    }
}
