<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\PrivacyPolicy;
use App\Livewire\TermsAndConditions;
use App\Http\Controllers\RadioController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Api\MobileApiController;
use App\Models\Radio;
use Illuminate\Http\Request;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Auth\LoginController;

// Autenticación admin
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Ruta para la página de inicio (todas las emisoras)
Route::get('/', [RadioController::class, 'index'])->name('emisoras.index');

// Ruta para búsqueda de emisoras
Route::get('/buscar', [RadioController::class, 'buscar'])->name('buscar');

// Ruta para agregar a favoritos (rate limited: 30 requests/min)
Route::post('/favoritos/agregar/{id}', function($id) {
    return response()->json(['success' => true]);
})->middleware('api.rate.limit:30,1')->name('agregar.favorito');

Route::post('/radio/register-play', [RadioController::class, 'registerPlay'])
    ->middleware('api.rate.limit:30,1')
    ->name('radio.register-play');

// Ruta para el Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-images.xml', [SitemapController::class, 'imageSitemap'])->name('sitemap.images');

// Rutas para el Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/buscar', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/categoria/{category}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{tag}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Rutas para las páginas legales e informativas
Route::get('/terminos', function() { return view('livewire.terms-and-conditions'); })->name('terminos');
Route::get('/privacidad', function() { return view('livewire.privacy-policy'); })->name('privacidad');
Route::get('/sobre-nosotros', function() { return view('about'); })->name('about');

// Ruta para la página de contacto
Route::get('/contacto', [App\Http\Controllers\ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [App\Http\Controllers\ContactoController::class, 'store'])
    ->middleware('api.rate.limit:5,1')
    ->name('contacto.store');

// Rutas para valoración de emisoras (rate limited: 10 requests/min para prevenir spam)
Route::post('/emisoras/rate', [App\Http\Controllers\RatingController::class, 'rateRadio'])
    ->middleware('api.rate.limit:10,1')
    ->name('emisoras.rate');
Route::get('/emisoras/user-rating/{radio}', [App\Http\Controllers\RatingController::class, 'getUserRating'])
    ->middleware('api.rate.limit:30,1')
    ->name('emisoras.user-rating');

// Ruta para mostrar los detalles de una emisora por su slug
Route::get('/emisoras/{slug}', [RadioController::class, 'show'])->name('emisoras.show');

// Ruta para mostrar las emisoras de una ciudad por su slug
Route::get('/ciudades/{slug}', [RadioController::class, 'emisorasPorCiudad'])->name('ciudades.show');

// Ruta para mostrar la lista de todas las ciudades
Route::get('/ciudades', [RadioController::class, 'indexCiudades'])->name('ciudades.index');

// Ruta para la página de favoritos
Route::get('/favoritos', [RadioController::class, 'favoritos'])->name('favoritos');

// Ruta API para obtener emisoras favoritas (rate limited: 30 requests/min)
Route::post('/api/favoritos', [RadioController::class, 'obtenerFavoritos'])
    ->middleware('api.rate.limit:30,1')
    ->name('api.favoritos');

// Ruta API para obtener la canción actual y oyentes de una emisora (rate limited: 60 requests/min)
Route::get('/api/radio/current-track/{id}', [RadioController::class, 'getCurrentTrack'])
    ->middleware('api.rate.limit:60,1')
    ->name('radio.current-track');

// API móvil - reemplaza appdomiradios/api/api.php (rate limited: 100 requests/hora)
Route::get('/api/mobile', [MobileApiController::class, 'handle'])
    ->middleware('api.rate.limit:100,60')
    ->name('api.mobile');

// Compatibilidad con ruta legacy de las apps móviles
Route::get('/appdomiradios/api/api.php', [MobileApiController::class, 'handle'])
    ->middleware('api.rate.limit:100,60')
    ->name('api.mobile.legacy');

// Compatibilidad con app Android (panel.domiradios.com.do/api/api.php)
Route::get('/api/api.php', [MobileApiController::class, 'handle'])
    ->middleware('api.rate.limit:100,60')
    ->name('api.mobile.android');

// Redirects 301 para URLs legacy
Route::redirect('/terminos-y-condiciones', '/terminos', 301);
Route::redirect('/politica-de-privacidad', '/privacidad', 301);












