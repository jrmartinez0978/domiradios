<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use App\Models\Radio;
use App\Observers\RadioObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Registrar observers
        Radio::observe(RadioObserver::class);
        
        // Tu lógica general de app aquí
    }
}

