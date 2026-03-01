<?php

use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\ConfigController;
use App\Http\Controllers\Api\V1\GenreController;
use App\Http\Controllers\Api\V1\RadioController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\ThemeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api.key', 'api.rate.limit:120,1'])->group(function () {
    // Static routes BEFORE {id} parameter routes
    Route::get('radios/featured', [RadioController::class, 'featured']);
    Route::get('radios/search', [RadioController::class, 'search']);
    Route::post('radios/favorites', [RadioController::class, 'favorites']);

    Route::get('radios', [RadioController::class, 'index']);
    Route::get('radios/{id}', [RadioController::class, 'show'])->whereNumber('id');
    Route::get('radios/{id}/current-track', [RadioController::class, 'currentTrack'])->whereNumber('id');
    Route::post('radios/{id}/play', [RadioController::class, 'registerPlay'])->whereNumber('id')->middleware('api.rate.limit:30,1');
    Route::post('radios/{id}/rate', [RadioController::class, 'rate'])->whereNumber('id')->middleware('api.rate.limit:10,1');
    Route::get('radios/{id}/rating', [RadioController::class, 'userRating'])->whereNumber('id');

    Route::get('genres', [GenreController::class, 'index']);
    Route::get('cities', [CityController::class, 'index']);
    Route::get('themes', [ThemeController::class, 'index']);
    Route::get('config', [ConfigController::class, 'index']);
    Route::get('settings', [SettingController::class, 'index']);
});
