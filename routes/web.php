<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RadioController;

// Ruta para la página de inicio
Route::get('/', function () {
    return view('emisoras');
})->name('inicio');

// Ruta para mostrar los detalles de una emisora por su slug
Route::get('/emisoras/{slug}', [RadioController::class, 'show'])->name('emisoras.show');

// Ruta para mostrar las emisoras de una ciudad por su slug
Route::get('/ciudades/{slug}', [RadioController::class, 'emisorasPorCiudad'])->name('ciudades.show');

// Ruta para mostrar la lista de todas las ciudades
Route::get('/ciudades', [RadioController::class, 'indexCiudades'])->name('ciudades.index');

Route::get('/favoritos', [RadioController::class, 'favoritos'])->name('favoritos');

// Ruta API para obtener emisoras favoritas (puede usarse en una versión más avanzada si se implementa autenticación)
Route::post('/api/favoritos', [RadioController::class, 'obtenerFavoritos'])->name('api.favoritos');













