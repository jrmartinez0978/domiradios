<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RadioSeoController;
use App\Models\Radio;

// Ruta de prueba - accesible en /api/test
Route::get('test', function() {
    return response()->json(['status' => 'API working!']);
});

// Ruta pública para listar radios
Route::get('radios', function() {
    return response()->json(Radio::where('isActive', true)->get());
});

// Rutas SEO usando el middleware por nombre de clase completo y agrupadas por prefijo
Route::middleware([\App\Http\Middleware\VerifySeoToken::class])->prefix('radios')->group(function () {
    // Rutas de consulta SEO
    Route::get('/seo', [RadioSeoController::class, 'index']);
    Route::get('/{radio}/seo', [RadioSeoController::class, 'show']);
    
    // Rutas de actualización y backup
    Route::patch('/{radio}/seo', [RadioSeoController::class, 'update']);
    Route::post('/{radio}/seo/backup', [RadioSeoController::class, 'backup']);
    Route::match(['get', 'post'], '/{radio}/seo-backup', [RadioSeoController::class, 'backup']);
    
    // Nuevas utilidades
    Route::match(['get', 'post'], '/check-duplicates', [RadioSeoController::class, 'checkDuplicates']);
    Route::post('/cache/clear', [RadioSeoController::class, 'clearCache']);
});
