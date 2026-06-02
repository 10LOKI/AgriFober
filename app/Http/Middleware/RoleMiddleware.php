<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Supports multiple roles via pipe (|) or multiple parameters
     * Example: 'role:admin' or 'role:admin,agriculteur'
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }
        
        // Flatten all role parameters (handles both pipe and comma syntax)
        $allRoles = collect($roles)->flatMap(function ($roleString) {
            return explode('|', $roleString);
        })->filter()->all();
        
        $userRole = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;

        $hasRole = collect($allRoles)->contains(fn($role) => $userRole === $role);
        
        if (!$hasRole) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient permissions. Required role(s): ' . implode('|', $allRoles)
            ], 403);
        }
        
        return $next($request);
    }
}
