<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\Radio;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http; // Importación necesaria
use Illuminate\Support\Facades\Log;  // Importación necesaria para Log
use App\Models\Visita;



class RadioController extends Controller
{
    private const DEFAULT_TRACK_INFO = 'Sin información';
    private const SOURCE_SONICPANEL = 'SonicPanel';
    private const SOURCE_SHOUTCAST = 'Shoutcast';
    private const SOURCE_ICECAST = 'Icecast';
    private const SOURCE_AZURACAST = 'AzuraCast';

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

        // Buscar emisoras relacionadas basadas en los mismos géneros
        $relatedRadios = Radio::whereHas('genres', function ($query) use ($radio) {
            $query->whereIn('genres.id', $radio->genres->pluck('id'));
        })
        ->where('id', '!=', $radio->id)
        ->limit(5)
        ->get();

        // Generar la URL canónica
        $canonical_url = route('emisoras.show', ['slug' => $radio->slug]);

        // Si los tags están guardados como una cadena de texto, se separan por comas
        $meta_keywords = $radio->tags; // Asumimos que tags es una cadena como 'rock, pop, baladas'

        // Retornar la vista con los metadatos para SEO y las emisoras relacionadas
        return view('detalles', compact('radio', 'relatedRadios'))
               ->with([
                    'meta_title' => $radio->name . ' - Escucha en vivo',
                    'meta_description' => strip_tags($radio->description),
                    'meta_keywords' => $meta_keywords,  // Usar los tags como keywords directamente
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
            $query->where('genres.id', $genre->id);
        })->get();

        // Generar la URL canónica
        $canonical_url = route('ciudades.show', ['slug' => $genre->slug]);

        // Retornar la vista con las emisoras de la ciudad y los metadatos para SEO
        return view('emisoras_por_ciudad', compact('radios', 'genre'))
               ->with([
                    'meta_title' => 'Emisoras en ' . $genre->name . ' - Directorio de Emisoras',
                    'meta_description' => 'Descubre las emisoras de radio en ' . $genre->name . ' y sus géneros.',
                    'meta_keywords' => 'emisoras, radio, ' . $genre->name,
                    'canonical_url' => $canonical_url
               ]);
    }

    // Método para mostrar todas las ciudades (géneros)
    public function indexCiudades()
    {
        // Obtener todos los géneros (ciudades)
        $genres = Genre::all();

        // Generar la URL canónica
        $canonical_url = route('ciudades.index');

        // Retornar la vista con todas las ciudades y los metadatos SEO
        return view('ciudades', compact('genres'))
               ->with([
                    'meta_title' => 'Ciudades con Emisoras - Directorio de Emisoras',
                    'meta_description' => 'Encuentra emisoras de radio por ciudad en nuestro directorio completo.',
                    'meta_keywords' => 'emisoras, radio, ciudades',
                    'canonical_url' => $canonical_url
               ]);
    }

    // Método para mostrar todas las emisoras
    public function index()
    {
        $radios = Radio::all();

        // Generar la URL canónica
        $canonical_url = route('emisoras.index');

        // Retornar la vista 'emisoras' con los datos de las radios y los metadatos SEO
        return view('emisoras', compact('radios'))
               ->with([
                    'meta_title' => 'Todas las Emisoras - Directorio de Emisoras',
                    'meta_description' => 'Descubre todas las emisoras de radio disponibles en nuestro directorio.',
                    'meta_keywords' => 'emisoras, radio, géneros, estaciones',
                    'canonical_url' => $canonical_url
               ]);
    }
// Método para obtener la canción actual y oyentes
public function getCurrentTrack($id)
{
    $radio = Radio::findOrFail($id);

    $linkRadio = $radio->link_radio; // Enlace completo incluyendo puerto y mount point

    Log::info('Processing radio ID ' . $id . ' with link_radio ' . $linkRadio . ' and source_radio ' . $radio->source_radio);

    try {
        // Parsear el link_radio para extraer componentes
        $parsedUrl = parse_url($linkRadio);
        $scheme = $parsedUrl['scheme'] ?? 'http'; // Default to http
        $host = $parsedUrl['host'] ?? null;
        $portFromUrl = $parsedUrl['port'] ?? null; // Port explicitly in link_radio
        $path = $parsedUrl['path'] ?? '';

        if (!$host) {
            Log::error("Could not parse host from link_radio '{$linkRadio}' for radio ID {$id}.");
            throw new \Exception("Invalid link_radio: host missing.");
        }

        // Construir la URL base del servidor de streaming (scheme://host:port)
        $serverBaseUrl = $scheme . '://' . $host;
        if ($portFromUrl) {
            $serverBaseUrl .= ':' . $portFromUrl;
        }
        
        Log::info("Radio ID {$id}: Server base URL determined as '{$serverBaseUrl}'. Path: '{$path}'");

        $currentTrack = self::DEFAULT_TRACK_INFO;
        $listeners = $this->getFictitiousListeners($id);
        $infoUrl = '';

        switch ($radio->source_radio) {
            case self::SOURCE_SONICPANEL:
                $sonicPort = $portFromUrl; // Use port from URL if available
                if (!$sonicPort) {
                    // Attempt to extract from path if not in host part e.g. http://sonicpanel.example.com/12345/stream
                    // Regex: /<digits>/ where digits are the port/ID for SonicPanel
                    if (preg_match('/\/(\d+)(?:\/|$)/', $path, $matches)) {
                        $sonicPort = $matches[1];
                        Log::info("Radio ID {$id}: SonicPanel port '{$sonicPort}' extracted from path '{$path}'.");
                    } else {
                        Log::error("Radio ID {$id}: SonicPanel port could not be determined from URL '{$linkRadio}' or path '{$path}'.");
                        throw new \Exception("SonicPanel port missing for radio ID {$id}.");
                    }
                }
                // SonicPanel API endpoint uses the main host, not the streaming port in its URL.
                // The 'p' parameter is the port/ID that was extracted.
                $infoUrl = $scheme . '://' . $host . '/cp/get_info.php?p=' . $sonicPort;
                Log::info("Radio ID {$id}: SonicPanel infoUrl: " . $infoUrl);
                
                $response = Http::timeout(5)->get($infoUrl);
                if ($response->failed()) {
                    Log::error("Radio ID {$id}: HTTP request failed for SonicPanel ({$infoUrl}): Status " . $response->status() . ". Body: " . $response->body());
                    throw new \Exception("HTTP request failed for SonicPanel for radio ID {$id}.");
                }
                $data = $response->body();
                Log::info("Radio ID {$id}: Received response from SonicPanel: " . $data);
                $json = json_decode($data, true);
                if ($json === null) {
                    Log::error("Radio ID {$id}: Failed to parse JSON from SonicPanel ({$infoUrl}). Data: " . $data);
                    throw new \Exception("JSON parsing failed for SonicPanel for radio ID {$id}.");
                }
                $currentTrack = $json['title'] ?? self::DEFAULT_TRACK_INFO;
                $listeners = $json['listeners'] ?? $this->getFictitiousListeners($id);
                break;

            case self::SOURCE_SHOUTCAST:
                // Shoutcast stats are typically at /stats?sid=1 or /7.html (older versions)
                // $serverBaseUrl should be correct here (e.g. http://shoutcast.example.com:8000)
                $infoUrl = $serverBaseUrl . '/stats?sid=1'; // Common path for Shoutcast v2
                // Alternative for Shoutcast v1 (less common now): $serverBaseUrl . '/7.html';
                Log::info("Radio ID {$id}: Shoutcast infoUrl: " . $infoUrl);

                $response = Http::timeout(5)->get($infoUrl);
                if ($response->failed()) {
                    Log::error("Radio ID {$id}: HTTP request failed for Shoutcast ({$infoUrl}): Status " . $response->status() . ". Body: " . $response->body());
                    // Try parsing as HTML for 7.html type response if XML fails
                    if (strpos($response->body(), '<HTML>') !== false) {
                         // Attempt to parse HTML (basic string search for simplicity)
                        if (preg_match('/Current Song: <\/font><\/td><td class=default><b>(.*?)<\/b><\/td>/i', $response->body(), $songMatches)) {
                            $currentTrack = trim($songMatches[1]);
                        }
                        if (preg_match('/Current Listeners: <\/font><\/td><td class=default><b>(\d+)<\/b><\/td>/i', $response->body(), $listenerMatches)) {
                            $listeners = (int)trim($listenerMatches[1]);
                        } else {
                             $listeners = $this->getFictitiousListeners($id); // Fallback if listeners not found
                        }
                        break; // Parsed from HTML, exit switch case
                    }
                    throw new \Exception("HTTP request failed for Shoutcast for radio ID {$id}.");
                }
                $data = $response->body();
                Log::info("Radio ID {$id}: Received response from Shoutcast: " . $data);
                // Shoutcast v2 /stats typically returns XML
                // It might also return HTML if it's an older version or misconfigured
                libxml_use_internal_errors(true); // Suppress warnings for invalid XML
                $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml === false) {
                    $xmlErrors = [];
                    foreach (libxml_get_errors() as $error) { $xmlErrors[] = $error->message; }
                    libxml_clear_errors();
                    Log::error("Radio ID {$id}: Failed to parse XML from Shoutcast ({$infoUrl}). Errors: " . implode("; ", $xmlErrors) . ". Data: " . $data);
                     // Check if it's the HTML format (7.html)
                    if (preg_match('/Current Song: <\/font><\/td><td class=default><b>(.*?)<\/b><\/td>/i', $data, $songMatches)) {
                        $currentTrack = trim($songMatches[1]);
                    }
                    if (preg_match('/Current Listeners: <\/font><\/td><td class=default><b>(\d+)<\/b><\/td>/i', $data, $listenerMatches)) {
                        $listeners = (int)trim($listenerMatches[1]);
                    } else {
                         $listeners = $this->getFictitiousListeners($id);
                    }
                } else {
                    $currentTrack = isset($xml->SONGTITLE) ? (string) $xml->SONGTITLE : self::DEFAULT_TRACK_INFO;
                    $listeners = isset($xml->CURRENTLISTENERS) ? (int) $xml->CURRENTLISTENERS : $this->getFictitiousListeners($id);
                }
                break;

            case self::SOURCE_ICECAST:
                // Try standard JSON stats endpoint first: /status-json.xsl or /admin/stats (older)
                // $serverBaseUrl is scheme://host:port_from_url
                $infoUrl = $serverBaseUrl . '/status-json.xsl';
                Log::info("Radio ID {$id}: Icecast attempting JSON stats URL: " . $infoUrl);

                $response = Http::timeout(5)->get($infoUrl);
                if ($response->successful()) {
                    $data = $response->body();
                    Log::info("Radio ID {$id}: Received response from Icecast JSON stats: " . $data);
                    $json = json_decode($data, true);
                    if ($json !== null && isset($json['icestats'])) {
                        $icestats = $json['icestats'];
                        $source = null;
                        if (isset($icestats['source']) && is_array($icestats['source'])) {
                            // Multiple sources, try to find one matching the path or take the first
                            if (!empty($path) && $path !== '/') {
                                foreach ($icestats['source'] as $src) {
                                    if (isset($src['listenurl']) && strpos($src['listenurl'], $path) !== false) {
                                        $source = $src;
                                        break;
                                    }
                                }
                            }
                            if (!$source && !empty($icestats['source'])) {
                                $source = $icestats['source'][0]; // Default to first source
                            }
                        } elseif (isset($icestats['source'])) {
                            $source = $icestats['source']; // Single source
                        }

                        if ($source) {
                            $currentTrack = $source['title'] ?? $source['song'] ?? self::DEFAULT_TRACK_INFO;
                            $listeners = $source['listeners'] ?? $this->getFictitiousListeners($id);
                        } else {
                            Log::warning("Radio ID {$id}: Icecast JSON stats parsed, but no valid source found or source format unexpected. Data: " . $data);
                            // Fallback to XSPF if JSON parsing didn't yield results
                            goto icecast_xspf_fallback;
                        }
                    } else {
                        Log::warning("Radio ID {$id}: Failed to parse JSON from Icecast or 'icestats' field missing ({$infoUrl}). Data: " . $data);
                        goto icecast_xspf_fallback; // Try XSPF if JSON fails
                    }
                } else {
                    Log::warning("Radio ID {$id}: Icecast JSON stats request failed ({$infoUrl}): Status " . $response->status() . ". Trying XSPF fallback. Body: " . $response->body());
                    icecast_xspf_fallback:
                    $infoUrl = $linkRadio . '.xspf'; // $linkRadio includes the mountpoint
                    Log::info("Radio ID {$id}: Icecast XSPF infoUrl: " . $infoUrl);
                    $response = Http::timeout(5)->get($infoUrl);
                    if ($response->failed()) {
                        Log::error("Radio ID {$id}: HTTP request failed for Icecast XSPF ({$infoUrl}): Status " . $response->status() . ". Body: " . $response->body());
                        throw new \Exception("HTTP request failed for Icecast XSPF for radio ID {$id}.");
                    }
                    $data = $response->body();
                    Log::info("Radio ID {$id}: Received response from Icecast XSPF: " . $data);
                    libxml_use_internal_errors(true);
                    $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
                    if ($xml === false) {
                        $xmlErrors = [];
                        foreach (libxml_get_errors() as $error) { $xmlErrors[] = $error->message; }
                        libxml_clear_errors();
                        Log::error("Radio ID {$id}: Failed to parse XML from Icecast XSPF ({$infoUrl}). Errors: " . implode("; ", $xmlErrors) . ". Data: " . $data);
                        throw new \Exception("XML parsing failed for Icecast XSPF for radio ID {$id}.");
                    }
                    $namespaces = $xml->getNamespaces(true);
                    $xml->registerXPathNamespace('x', $namespaces[''] ?? 'http://xspf.org/ns/0/'); // Default XSPF namespace
                    $titleNode = $xml->xpath('//x:trackList/x:track/x:title');
                    $currentTrack = isset($titleNode[0]) ? (string) $titleNode[0] : self::DEFAULT_TRACK_INFO;
                    $annotationNode = $xml->xpath('//x:trackList/x:track/x:annotation');
                    if (isset($annotationNode[0])) {
                        $annotationText = (string) $annotationNode[0];
                        if (preg_match('/Current Listeners:\s*(\d+)/i', $annotationText, $listenerMatches)) {
                            $listeners = (int) $listenerMatches[1];
                        } else {
                            Log::warning("Radio ID {$id}: Could not parse listener count from Icecast XSPF annotation. Annotation: " . $annotationText);
                            $listeners = $this->getFictitiousListeners($id);
                        }
                    } else {
                        $listeners = $this->getFictitiousListeners($id);
                    }
                }
                break;

            case self::SOURCE_AZURACAST:
                // $serverBaseUrl is scheme://host:port_from_url
                // AzuraCast API is typically at /api/nowplaying or /api/nowplaying/station_shortcode
                // If $linkRadio contains the station shortcode in path, it's complex.
                // Assuming $serverBaseUrl is the base of AzuraCast instance.
                // A common pattern is link_radio points to stream, and API is at the base.
                // Example: link_radio = http://azura.server.com/radio/8000/main.mp3
                // API might be http://azura.server.com/api/nowplaying/1 (if station id is 1)
                // or http://azura.server.com/api/nowplaying (if only one station or default)

                // Let's try a generic /api/nowplaying on the $serverBaseUrl first.
                // If $linkRadio is like http://azura.server.com/radio/station_id/stream,
                // we might need to extract 'station_id' if AzuraCast API requires it.
                // The current code uses $baseUrl which is $serverBaseUrl.
                $infoUrl = $serverBaseUrl . '/api/nowplaying'; 
                Log::info("Radio ID {$id}: AzuraCast infoUrl: " . $infoUrl);

                $response = Http::timeout(5)->get($infoUrl);
                if ($response->failed()) {
                    // Attempt to guess station-specific API if $path has structure like /radio/<id>/stream
                    if (preg_match('/\/radio\/([^\/]+)\//', $path, $pathMatches)) {
                        $stationId = $pathMatches[1];
                        $specificInfoUrl = $serverBaseUrl . '/api/nowplaying/' . $stationId;
                        Log::info("Radio ID {$id}: AzuraCast generic API failed. Trying station-specific: " . $specificInfoUrl);
                        $response = Http::timeout(5)->get($specificInfoUrl);
                        if ($response->failed()) {
                             Log::error("Radio ID {$id}: HTTP request failed for AzuraCast (specific: {$specificInfoUrl}): Status " . $response->status() . ". Body: " . $response->body());
                             throw new \Exception("HTTP request failed for AzuraCast (specific endpoint) for radio ID {$id}.");
                        }
                        $infoUrl = $specificInfoUrl; // Update infoUrl for logging if successful
                    } else {
                        Log::error("Radio ID {$id}: HTTP request failed for AzuraCast (generic: {$infoUrl}): Status " . $response->status() . ". Body: " . $response->body());
                        throw new \Exception("HTTP request failed for AzuraCast (generic endpoint) for radio ID {$id}.");
                    }
                }
                $data = $response->body();
                Log::info("Radio ID {$id}: Received response from AzuraCast: " . $data);
                $json = json_decode($data, true);
                if ($json === null) {
                    Log::error("Radio ID {$id}: Failed to parse JSON from AzuraCast ({$infoUrl}). Data: " . $data);
                    throw new \Exception("JSON parsing failed for AzuraCast for radio ID {$id}.");
                }
                $currentTrack = $json['now_playing']['song']['title'] ?? $json['now_playing']['song']['text'] ?? self::DEFAULT_TRACK_INFO;
                $listeners = $json['listeners']['current'] ?? $json['listeners']['total'] ?? $this->getFictitiousListeners($id);
                break;

            default:
                Log::error("Radio ID {$id}: Tipo de fuente no soportado: " . $radio->source_radio);
                // No need to throw exception, will use default sin informacion/fictitious listeners
                break;
        }

        Log::info("Radio ID {$id}: Returning currentTrack: '{$currentTrack}', listeners: {$listeners} for source {$radio->source_radio}");

        return response()->json([
            'currentTrack' => $currentTrack,
            'listeners' => $listeners,
        ]);

    } catch (\Throwable $e) { // Catch Throwable for broader error catching including Http client errors
        Log::error("Radio ID {$id}: Exception in getCurrentTrack: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
        // Fallback to default information on any exception
        return response()->json([
            'currentTrack' => self::DEFAULT_TRACK_INFO,
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

public function registerPlay(Request $request)
    {
        $radioId = $request->input('radio_id');

        // Validar que el radio_id existe
        if (!Radio::where('id', $radioId)->exists()) {
            return response()->json(['error' => 'Emisora no encontrada'], 404);
        }

        // Registrar la visita
        Visita::create(['radio_id' => $radioId]);

        return response()->json(['message' => 'Visita registrada']);
    }

}


