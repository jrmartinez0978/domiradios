<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('mobile.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API key not configured.',
                'data' => null,
            ], 500);
        }

        $providedKey = $request->bearerToken() ?? $request->header('X-Api-Key');

        if (! $providedKey || ! hash_equals($apiKey, $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing API key.',
                'data' => null,
            ], 401);
        }

        return $next($request);
    }
}
