<?php

namespace App\Http\Controllers;

use App\Models\Radio;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        $linkRadio = $radio->link_radio; // Enlace completo incluyendo puerto y mount point

        try {
            // Parsear el link_radio para extraer componentes
            $parsedUrl = parse_url($linkRadio);
            $scheme = $parsedUrl['scheme'] ?? 'http';
            $host = $parsedUrl['host'] ?? '';
            $port = $parsedUrl['port'] ?? '';
            $path = $parsedUrl['path'] ?? '';

            // Construir la URL base
            $baseUrl = $scheme . '://' . $host;
            if ($port) {
                $baseUrl .= ':' . $port;
            }

            switch ($radio->source_radio) {
                case 'SonicPanel':
                    // Construir la URL para obtener información
                    // URL: baseUrl + '/cp/get_info.php?p=' + port
                    if (!$port) {
                        // Intentar extraer el puerto del path
                        preg_match('/\/(\d+)\//', $path, $matches);
                        if (isset($matches[1])) {
                            $port = $matches[1];
                        } else {
                            return response()->json(['error' => 'No se pudo extraer el puerto'], 400);
                        }
                    }
                    $infoUrl = $scheme . '://' . $host . '/cp/get_info.php?p=' . $port;
                    break;

                case 'Shoutcast':
                    // URL: baseUrl + '/stats?sid=1&json=1'
                    $infoUrl = $baseUrl . '/stats?sid=1&json=1';
                    break;

                case 'Icecast':
                    // Añadir '.xspf' al link_radio para obtener el archivo XSPF
                    $infoUrl = $linkRadio . '.xspf';
                    break;

                case 'AzuraCast':
                    // URL: baseUrl + '/api/nowplaying'
                    $infoUrl = $baseUrl . '/api/nowplaying';
                    break;

                default:
                    return response()->json(['error' => 'Tipo de fuente no soportado'], 400);
            }

            // Realizar la solicitud HTTP
            $response = Http::get($infoUrl);
            $data = $response->body();

            if ($radio->source_radio === 'Icecast') {
                // Parsear el XML del XSPF
                $xml = simplexml_load_string($data);
                if ($xml !== false) {
                    $trackList = $xml->xpath('/playlist/trackList/track');
                    if (isset($trackList[0])) {
                        $currentTrack = (string) $trackList[0]->title ?? 'Sin información';
                    } else {
                        $currentTrack = 'Sin información';
                    }
                } else {
                    $currentTrack = 'Sin información';
                }
                // Generar número de oyentes ficticio
                $listeners = $this->getFictitiousListeners($id);
            } elseif ($radio->source_radio === 'SonicPanel') {
                $json = json_decode($data, true);
                $currentTrack = $json['song'] ?? 'Sin información';
                $listeners = $json['listeners'] ?? $this->getFictitiousListeners($id);
            } elseif ($radio->source_radio === 'Shoutcast') {
                $json = json_decode($data, true);
                $currentTrack = $json['songtitle'] ?? 'Sin información';
                $listeners = $json['currentlisteners'] ?? $this->getFictitiousListeners($id);
            } elseif ($radio->source_radio === 'AzuraCast') {
                $json = json_decode($data, true);
                $currentTrack = $json['now_playing']['song']['title'] ?? 'Sin información';
                $listeners = $json['listeners']['current'] ?? $this->getFictitiousListeners($id);
            } else {
                $currentTrack = 'Sin información';
                $listeners = $this->getFictitiousListeners($id);
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

    // Método para generar un número de oyentes ficticio y realista
    private function getFictitiousListeners($radioId)
    {
        // Utilizar caché para mantener el conteo entre solicitudes
        $cacheKey = 'listeners_' . $radioId;
        $listeners = Cache::get($cacheKey, rand(1, 100));

        // Cambiar el número de oyentes de forma aleatoria entre -3 y +3
        $change = rand(-3, 3);
        $listeners += $change;

        // Asegurarse de que el número de oyentes esté entre 1 y 100
        $listeners = max(1, min($listeners, 100));

        // Guardar el nuevo valor en caché por un tiempo definido (ejemplo: 1 minuto)
        Cache::put($cacheKey, $listeners, now()->addMinutes(1));

        return $listeners;
    }
}
