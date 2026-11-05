<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role ?? null, ['dueño', 'owner'], true)) {
            return response()->json(['message' => 'Solo el dueño puede realizar esta acción.'], 403);
        }

        return $next($request);
    }
}
