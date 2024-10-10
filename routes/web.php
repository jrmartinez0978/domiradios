<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\PrivacyPolicy;
use App\Livewire\TermsAndConditions;
use App\Http\Controllers\RadioController;
use App\Models\Radio;
use Illuminate\Http\Request;
use App\Http\Controllers\SitemapController;

// Ruta para la página de inicio
Route::get('/', function () {
    return view('emisoras');
})->name('inicio');

// Ruta para obtener emisoras favoritas (si no estás usando autenticación)
Route::post('/api/favoritos', function (Request $request) {
    $ids = $request->input('ids', []);
    $favoritos = Radio::whereIn('id', $ids)->get();

    return response()->json($favoritos);
});

Route::post('/radio/register-play', [RadioController::class, 'registerPlay'])->name('radio.register-play');

// Ruta para el Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Rutas para las páginas legales
Route::get('/appdomiradios/privacy-policy', PrivacyPolicy::class);
Route::get('/appdomiradios/terms-and-conditions', TermsAndConditions::class);

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












