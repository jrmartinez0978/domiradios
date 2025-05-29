<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerScripts([
                'https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0',
            ], true);
        });
    }
}
