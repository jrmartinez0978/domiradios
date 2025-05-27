<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\PrivacyPolicy;
use App\Livewire\TermsAndConditions;
use App\Http\Controllers\RadioController;
use App\Models\Radio;
use Illuminate\Http\Request;
use App\Http\Controllers\SitemapController;

// Ruta para la página de inicio (todas las emisoras)
Route::get('/', [RadioController::class, 'index'])->name('emisoras.index');

// Ruta para búsqueda de emisoras
Route::get('/buscar', [RadioController::class, 'buscar'])->name('buscar');

// Ruta para agregar a favoritos
Route::post('/favoritos/agregar/{id}', function($id) {
    return response()->json(['success' => true]);
})->name('agregar.favorito');

Route::post('/radio/register-play', [RadioController::class, 'registerPlay'])->name('radio.register-play');

// Ruta para el Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Rutas para las páginas legales
Route::get('/terminos', function() { return view('livewire.terms-and-conditions'); })->name('terminos');
Route::get('/privacidad', function() { return view('livewire.privacy-policy'); })->name('privacidad');

// Ruta para la página de contacto
Route::get('/contacto', [App\Http\Controllers\ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [App\Http\Controllers\ContactoController::class, 'store'])->name('contacto.store');

// Rutas para valoración de emisoras
Route::post('/emisoras/rate', [App\Http\Controllers\RatingController::class, 'rateRadio'])->name('emisoras.rate');
Route::get('/emisoras/user-rating/{radio}', [App\Http\Controllers\RatingController::class, 'getUserRating'])->name('emisoras.user-rating');

// Ruta para mostrar los detalles de una emisora por su slug
Route::get('/emisoras/{slug}', [RadioController::class, 'show'])->name('emisoras.show');

// Ruta para mostrar las emisoras de una ciudad por su slug
Route::get('/ciudades/{slug}', [RadioController::class, 'emisorasPorCiudad'])->name('ciudades.show');

// Ruta para mostrar la lista de todas las ciudades
Route::get('/ciudades', [RadioController::class, 'indexCiudades'])->name('ciudades.index');

// Ruta para la página de favoritos
Route::get('/favoritos', [RadioController::class, 'favoritos'])->name('favoritos');

// Ruta API para obtener emisoras favoritas
Route::post('/api/favoritos', [RadioController::class, 'obtenerFavoritos'])->name('api.favoritos');

// Ruta API para obtener la canción actual y oyentes de una emisora
Route::get('/api/radio/current-track/{id}', [RadioController::class, 'getCurrentTrack'])->name('radio.current-track');












