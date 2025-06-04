<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySeoToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // Intentar obtener el token de varias fuentes posibles
        $token = $request->header('X-SEO-TOKEN')        // Header estándar
            ?? $request->header('HTTP_X_SEO_TOKEN')     // Header con formato CGI
            ?? $request->input('token')                // Parámetro en body
            ?? $request->query('token')                // Parámetro en query string
            ?? $request->bearerToken();               // Token Bearer
        
        // Validar token
        if (!$token || $token !== config('seo.agent_token')) {
            return response()->json([
                'error' => 'Token SEO inválido o ausente',
                'help' => 'Asegúrate de incluir X-SEO-TOKEN en los headers o token en los parámetros'
            ], 401);
        }
        
        return $next($request);
    }
}
