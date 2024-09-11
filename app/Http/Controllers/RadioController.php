<?php

namespace App\Http\Controllers;

use App\Models\Radio; // Import the Radio model

class RadioController extends Controller
{
    public function show($slug)
    {
        // Buscar la emisora actual por su slug
        $radio = Radio::where('slug', $slug)->firstOrFail();

        // Buscar emisoras relacionadas basadas en la misma ciudad (género)
        $relatedRadios = Radio::whereHas('genres', function($query) use ($radio) {
            $query->whereIn('genres.id', $radio->genres->pluck('id')); // Especifica 'genres.id'
        })
        ->where('radios.id', '!=', $radio->id) // Especifica 'radios.id'
        ->limit(5)
        ->get();


        // Retornar la vista con la emisora y las emisoras relacionadas
        return view('detalles', compact('radio', 'relatedRadios'));
    }
}

