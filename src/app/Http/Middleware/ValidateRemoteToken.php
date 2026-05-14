<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ValidateRemoteToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Cache per token to avoid hitting L7 on every request
        $userData = Cache::remember("auth_token_{$token}", 300, function () use ($token) {
            $response = Http::withToken($token)
                ->get(config('services.auth_server.url') . '/api/auth/init');

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        });
        
        if (!$userData) {
            Cache::forget("auth_token_{$token}"); // don't cache failed tokens
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Store user data in the request for downstream use
        $request->merge(['auth_user' => $userData]);
        $request->attributes->set('auth_user', $userData);

        return $next($request);
    }
}
