<?php

namespace App\Http\Controllers;

use App\Models\Radio;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http; // Importación necesaria
use Illuminate\Support\Facades\Log;  // Importación necesaria para Log

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
    // Método para mostrar los detalles de una emisora por su slug
public function show($slug)
{
    // Buscar la emisora actual por su slug
    $radio = Radio::where('slug', $slug)->firstOrFail();

    // Buscar emisoras relacionadas basadas en la misma ciudad (género)
    $relatedRadios = Radio::whereHas('genres', function ($query) use ($radio) {
        $query->whereIn('genres.id', $radio->genres->pluck('id')); // Especificar 'genres.id'
    })
    ->where('id', '!=', $radio->id) // No usar 'radios.id', usar 'id' directamente porque es el modelo 'Radio'
    ->limit(5)
    ->get();

    // Generar la URL canónica
    $canonical_url = route('emisoras.show', ['slug' => $radio->slug]);

    // Retornar la vista con los metadatos para SEO y las emisoras relacionadas
    return view('detalles', compact('radio', 'relatedRadios'))
           ->with([
                'meta_title' => $radio->name . ' - Escucha en vivo',
                'meta_description' => strip_tags($radio->description),
                'meta_keywords' => $radio->tags,
                'canonical_url' => $canonical_url
           ]);
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

    Log::info('Processing radio ID ' . $id . ' with link_radio ' . $linkRadio);

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

        Log::info('Base URL: ' . $baseUrl);

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
                        Log::error('No se pudo extraer el puerto para SonicPanel');
                        return response()->json(['error' => 'No se pudo extraer el puerto'], 400);
                    }
                }
                $infoUrl = $scheme . '://' . $host . '/cp/get_info.php?p=' . $port;
                Log::info('SonicPanel infoUrl: ' . $infoUrl);
                break;

            case 'Shoutcast':
                // URL: baseUrl + '/stats?sid=1'
                $infoUrl = $baseUrl . '/stats?sid=1';
                Log::info('Shoutcast infoUrl: ' . $infoUrl);
                break;

            case 'Icecast':
                $infoUrl = $linkRadio . '.xspf';
                Log::info('Icecast infoUrl: ' . $infoUrl);
                break;

            case 'AzuraCast':
                $infoUrl = $baseUrl . '/api/nowplaying';
                Log::info('AzuraCast infoUrl: ' . $infoUrl);
                break;

            default:
                Log::error('Tipo de fuente no soportado: ' . $radio->source_radio);
                return response()->json(['error' => 'Tipo de fuente no soportado'], 400);
        }

        // Realizar la solicitud HTTP
        $response = Http::get($infoUrl);
        if ($response->failed()) {
            throw new \Exception('Failed to retrieve data from ' . $infoUrl);
        }
        $data = $response->body();
        Log::info('Received response: ' . $data);

        if ($radio->source_radio === 'SonicPanel') {
            Log::info('Parsing data for SonicPanel');
            $json = json_decode($data, true);
            if ($json === null) {
                throw new \Exception('Failed to parse JSON from SonicPanel');
            }

            $currentTrack = $json['title'] ?? 'Sin información';
            $listeners = $json['listeners'] ?? $this->getFictitiousListeners($id);
        } elseif ($radio->source_radio === 'Shoutcast') {
            Log::info('Parsing data for Shoutcast');
            $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($xml === false) {
                Log::error('Failed to parse XML from Shoutcast');
                throw new \Exception('Failed to parse XML from Shoutcast');
            }

            $currentTrack = isset($xml->SONGTITLE) ? (string) $xml->SONGTITLE : 'Sin información';
            $listeners = isset($xml->CURRENTLISTENERS) ? (int) $xml->CURRENTLISTENERS : $this->getFictitiousListeners($id);
        } elseif ($radio->source_radio === 'Icecast') {
            Log::info('Parsing data for Icecast');
            $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($xml === false) {
                Log::error('Failed to parse XML from Icecast');
                throw new \Exception('Failed to parse XML from Icecast');
            }

            // Namespace de XSPF
            $namespaces = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('x', $namespaces['']);

            // Extraer el título de la canción
            $titleNode = $xml->xpath('//x:trackList/x:track/x:title');
            if (isset($titleNode[0])) {
                $currentTrack = (string) $titleNode[0] ?: 'Sin información';
            } else {
                $currentTrack = 'Sin información';
            }

            // Extraer el número de oyentes desde el campo 'annotation'
            $annotationNode = $xml->xpath('//x:trackList/x:track/x:annotation');
            if (isset($annotationNode[0])) {
                $annotationText = (string) $annotationNode[0];
                // Buscar 'Current Listeners' en el texto de 'annotation'
                if (preg_match('/Current Listeners:\s*(\d+)/i', $annotationText, $matches)) {
                    $listeners = (int) $matches[1];
                } else {
                    $listeners = $this->getFictitiousListeners($id);
                }
            } else {
                $listeners = $this->getFictitiousListeners($id);
            }
        } elseif ($radio->source_radio === 'AzuraCast') {
            Log::info('Parsing data for AzuraCast');
            $json = json_decode($data, true);
            if ($json === null) {
                throw new \Exception('Failed to parse JSON from AzuraCast');
            }

            $currentTrack = $json['now_playing']['song']['title'] ?? 'Sin información';
            $listeners = $json['listeners']['current'] ?? $this->getFictitiousListeners($id);
        } else {
            $currentTrack = 'Sin información';
            $listeners = $this->getFictitiousListeners($id);
        }

        Log::info('Returning currentTrack: ' . $currentTrack . ', listeners: ' . $listeners);

        return response()->json([
            'currentTrack' => $currentTrack,
            'listeners' => $listeners,
        ]);
    } catch (\Exception $e) {
        Log::error('Error al obtener la información para la radio ID ' . $id . ': ' . $e->getMessage());

        // En lugar de devolver un error, devolver información por defecto
        return response()->json([
            'currentTrack' => 'Sin información',
            'listeners' => $this->getFictitiousListeners($id),
        ]);
    }
}

    // Método para generar un número de oyentes ficticio y realista
    private function getFictitiousListeners($radioId)
    {
        // Utilizar caché para mantener el conteo entre solicitudes
        $cacheKey = 'listeners_' . $radioId;
        $listeners = Cache::get($cacheKey, rand(1, 100));

        // Cambiar el número de oyentes de forma aleatoria entre -3 y +2
        $change = rand(-3, 2);
        $listeners += $change;

        // Asegurarse de que el número de oyentes esté entre 1 y 100
        $listeners = max(1, min($listeners, 100));

        // Guardar el nuevo valor en caché por un tiempo definido (ejemplo: 1 minuto)
        Cache::put($cacheKey, $listeners, now()->addMinutes(1));

        return $listeners;
    }
}


