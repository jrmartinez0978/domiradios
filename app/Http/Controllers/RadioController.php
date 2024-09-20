<?php

namespace App\Http\Controllers;

use App\Models\Radio;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Agregar esta línea
use Illuminate\Support\Facades\Log;  // Agregar esta línea para Log

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

    // Método para obtener la canción actual y oyentes
    public function getCurrentTrack($id)
    {
        $radio = Radio::findOrFail($id);

        switch ($radio->source_radio) {
            case 'SonicPanel':
                $url = $radio->link_radio . '/cp/get_info.php?p=' . $radio->port;
                break;
            case 'Shoutcast':
                $url = $radio->link_radio . '/stats?sid=1&json=1';
                break;
            case 'Icecast':
                $url = $radio->link_radio . '/status-json.xsl';
                break;
            case 'AzuraCast':
                $url = $radio->link_radio . '/api/nowplaying';
                break;
            default:
                return response()->json(['error' => 'Tipo de fuente no soportado'], 400);
        }

        try {
            $response = Http::get($url);
            $data = $response->body();
            $json = json_decode($data, true);

            // Lógica para extraer el título de la canción y los oyentes
            if ($radio->source_radio === 'SonicPanel') {
                $currentTrack = $json['title'] ?? 'Sin información';
                $listeners = $json['listeners'] ?? rand(1, 100);
            } elseif ($radio->source_radio === 'Shoutcast') {
                $currentTrack = $json['songtitle'] ?? 'Sin información';
                $listeners = $json['currentlisteners'] ?? rand(1, 100);
            } elseif ($radio->source_radio === 'Icecast') {
                if (isset($json['icestats']['source'])) {
                    $source = $json['icestats']['source'];
                    // Si hay múltiples fuentes, puede ser un array
                    if (isset($source[0])) {
                        $source = $source[0];  // Tomar la primera fuente
                    }
                    $currentTrack = $source['title'] ?? 'Sin información';
                    $listeners = $source['listeners'] ?? rand(1, 100);
                } else {
                    $currentTrack = 'Sin información';
                    $listeners = rand(1, 100);
                }
            } elseif ($radio->source_radio === 'AzuraCast') {
                $currentTrack = $json['now_playing']['song']['title'] ?? 'Sin información';
                $listeners = $json['listeners']['current'] ?? rand(1, 100);
            }

            return response()->json([
                'currentTrack' => $currentTrack,
                'listeners' => $listeners,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener la información para la radio ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener la información'], 500);
        }
    }
}
