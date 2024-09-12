<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RadioController;

Route::get('/', function () {
    return view('emisoras');
})->name('inicio');

Route::get('/emisoras/{slug}', [RadioController::class, 'show'])->name('emisoras.show');
Route::get('/ciudades/{slug}', [RadioController::class, 'emisorasPorCiudad'])->name('ciudades.show');
Route::get('/ciudades', [RadioController::class, 'indexCiudades'])->name('ciudades.index');
route::get('/emisoras_por_ciudad', [RadioController::class, 'emisorasPorCiudad'])->name('emisoras_por_ciudad');












