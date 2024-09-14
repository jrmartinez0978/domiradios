<?php

namespace App\Http\Controllers;

use App\Models\Radio;
use App\Models\Genre;
use Illuminate\Http\Request;

class RadioController extends Controller
{
    // Método para mostrar la vista de favoritos
    public function favoritos()
    {
        return view('favoritos');

    }
    // Nueva API para obtener emisoras favoritas
    public function obtenerFavoritos(Request $request)
    {
        $ids = $request->input('ids', []);  // Obtener los IDs de emisoras favoritos desde el frontend
        $favoritos = Radio::whereIn('id', $ids)->get();  // Obtener las emisoras que coincidan con esos IDs

        return response()->json($favoritos);
    }

    // Método para mostrar los detalles de una emisora por su slug
    public function show($slug)
    {
        // Buscar la emisora actual por su slug
        $radio = Radio::where('slug', $slug)->firstOrFail();

        // Buscar emisoras relacionadas basadas en la misma ciudad (género)
        $relatedRadios = Radio::whereHas('genres', function ($query) use ($radio) {
            $query->whereIn('genres.id', $radio->genres->pluck('id')); // Especificar 'genres.id'
        })
        ->where('radios.id', '!=', $radio->id) // Especificar 'radios.id'
        ->limit(5)
        ->get();

        // Retornar la vista con la emisora y las emisoras relacionadas
        return view('detalles', compact('radio', 'relatedRadios'));
    }

    // Método para mostrar las emisoras por ciudad (géneros)
    public function emisorasPorCiudad($slug)
    {
        // Buscar el género (ciudad) por su slug
        $genre = Genre::where('slug', $slug)->firstOrFail();

        // Obtener las emisoras relacionadas a esa ciudad
        $radios = Radio::whereHas('genres', function ($query) use ($genre) {
            $query->where('genres.id', $genre->id); // Especificar 'genres.id'
        })->get();

        // Retornar la vista con las emisoras de la ciudad
        return view('emisoras_por_ciudad', compact('radios', 'genre'));
    }

    // Método para mostrar todas las ciudades (géneros)
    public function indexCiudades()
    {
        // Obtener todos los géneros (ciudades)
        $genres = Genre::all();

        // Retornar la vista con todas las ciudades (géneros)
        return view('ciudades', compact('genres'));
    }

    // Método para mostrar todas las emisoras
    public function index()
    {
        $radios = Radio::all(); // Obtener todas las emisoras
        return view('emisoras', compact('radios')); // Retornar la vista 'emisoras' con los datos de las radios
    }
}
