<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RadioController;

Route::get('/', function () {
    return view('emisoras');
})->name('Inicio');


Route::get('/emisoras/{slug}', [RadioController::class, 'show'])->name('emisoras.show');

