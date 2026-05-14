<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->attributes->get('auth_user');

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $permissions = $user['user_permissions'] ?? [];
    
        if (!in_array($permission, $permissions)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
