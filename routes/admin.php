<?php

use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\RadioController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Resource routes
Route::resource('radios', RadioController::class);
Route::post('radios/bulk-action', [RadioController::class, 'bulkAction'])->name('radios.bulk-action');

Route::resource('genres', GenreController::class);
Route::resource('blog-posts', BlogPostController::class);
Route::resource('users', UserController::class);
Route::resource('themes', ThemeController::class);

// Settings (edit and update only)
Route::get('settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

// Configs (edit and update only)
Route::get('configs/edit', [ConfigController::class, 'edit'])->name('configs.edit');
Route::put('configs', [ConfigController::class, 'update'])->name('configs.update');
