<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Genre;
use App\Models\Radio;
use App\Models\Visita;
use App\Traits\HasSeo;
use Artesaos\SEOTools\Facades\JsonLd; // Importación necesaria
use Artesaos\SEOTools\Facades\SEOMeta;  // Importación necesaria para Log
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Importar el trait
use Illuminate\Support\Facades\Http; // Para structured data
use Illuminate\Support\Facades\Log; // Para canonical URLs

class RadioController extends Controller
{
    use HasSeo; // Usar el trait

    private const DEFAULT_TRACK_INFO = 'Sin información';

    private const SOURCE_SONICPANEL = 'SonicPanel';

    private const SOURCE_SHOUTCAST = 'Shoutcast';

    private const SOURCE_ICECAST = 'Icecast';

    private const SOURCE_AZURACAST = 'AzuraCast';

    private const SOURCE_JRMSTREAM = 'JRMStream';

    // Método para mostrar la vista de favoritos
    public function favoritos()
    {
        $this->setSeoData(
            'Mis Emisoras Favoritas',
            'Accede y gestiona tu lista de emisoras de radio dominicanas favoritas.',
            asset('img/domiradios-logo-og.jpg')
        );

        return view('favoritos');
    }

    // Método para buscar emisoras
    public function buscar(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        $query = $request->input('q');

        if (empty($query)) {
            // Si la búsqueda está vacía, podríamos redirigir o mostrar un mensaje.
            // Por ahora, establecemos SEO genérico para la página de búsqueda vacía si se llega aquí.
            $this->setSeoData(
                'Buscar Emisoras',
                'Encuentra tus emisoras dominicanas favoritas.',
                asset('img/domiradios-logo-og.jpg')
            );
            // Considera redirigir a la página principal o a una página de "no resultados" más específica.
            // return redirect()->route('emisoras.index'); // Opcional
        } else {
            $this->setSeoData(
                "Resultados para: \"{$query}\"",
                "Emisoras de radio dominicanas encontradas para tu búsqueda: \"{$query}\". Escucha en vivo.",
                asset('img/domiradios-logo-og.jpg')
            );
        }

        $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $query);
        $radios = Radio::with('genres')->where('isActive', true)
            ->where(function ($q) use ($escaped) {
                $q->where('name', 'like', "%$escaped%")
                    ->orWhere('bitrate', 'like', "%$escaped%")
                    ->orWhere('tags', 'like', "%$escaped%");
            })
            ->get();

        return view('emisoras', compact('radios', 'query'));
    }

    // Nueva API para obtener emisoras favoritas
    public function obtenerFavoritos(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|max:50',
            'ids.*' => 'integer|min:1',
        ]);

        $ids = $request->input('ids', []);
        $favoritos = Radio::whereIn('id', $ids)
            ->where('isActive', true)
            ->get();  // Obtener solo las emisoras activas que coincidan con esos IDs

        return response()->json($favoritos);
    }

    // Método para mostrar los detalles de una emisora por su slug
    public function show($slug)
    {
        // Buscar la emisora por su slug y verificar que esté activa
        $radio = Radio::with('genres')->where('slug', $slug)
            ->where('isActive', true)
            ->firstOrFail();

        // Obtener emisoras relacionadas: primero por géneros musicales, luego por ciudad
        $musicGenreIds = $radio->musicGenres->pluck('id');
        if ($musicGenreIds->isNotEmpty()) {
            $related = Radio::with('genres')->whereHas('genres', function ($query) use ($musicGenreIds) {
                $query->whereIn('genres.id', $musicGenreIds);
            })
                ->where('id', '!=', $radio->id)
                ->where('isActive', true)
                ->limit(5)
                ->get();
        } else {
            $related = Radio::with('genres')->whereHas('genres', function ($query) use ($radio) {
                $query->whereIn('genres.id', $radio->genres->pluck('id'));
            })
                ->where('id', '!=', $radio->id)
                ->where('isActive', true)
                ->limit(5)
                ->get();
        }

        // Generar la URL canónica
        $canonical_url = route('emisoras.show', ['slug' => $radio->slug]);

        // Preparar descripción optimizada para SEO
        $description = strip_tags($radio->description);
        $metaDescription = mb_substr($description, 0, 160);
        if (mb_strlen($description) > 160) {
            $metaDescription .= '...';
        }

        // Imagen de la emisora (usar asset completo para OpenGraph)
        $radioImage = $radio->img ? asset('storage/'.$radio->img) : asset('img/domiradios-logo-og.jpg');

        // Keywords: combinar tags + género + ciudad
        $keywords = [];
        if ($radio->tags) {
            $keywords = array_map('trim', explode(',', $radio->tags));
        }
        $keywords[] = $radio->name;
        $keywords[] = $radio->bitrate;
        if ($radio->address) {
            $keywords[] = $radio->address;
        }
        foreach ($radio->genres as $genre) {
            $keywords[] = $genre->name;
        }
        $keywords = array_unique(array_filter($keywords));

        // SEO 2025 Optimizado: Meta tags, OpenGraph, Twitter Cards
        $this->setSeoData(
            $radio->name.' - Escucha en vivo '.$radio->bitrate.' | Domiradios',
            $metaDescription,
            $radioImage,
            $keywords,
            $canonical_url
        );

        // Canonical URL
        SEOMeta::setCanonical($canonical_url);

        // JSON-LD is generated in the Blade template (detalles.blade.php) via inline schema
        // to avoid duplication with the SEOTools JsonLd facade

        return view('detalles', compact('radio', 'related'));
    }

    // Método para mostrar las emisoras por ciudad (géneros)
    public function emisorasPorCiudad($slug)
    {
        // Buscar el género (ciudad) por su slug
        $genre = Genre::where('slug', $slug)->firstOrFail();

        // Obtener las emisoras activas relacionadas a esa ciudad
        $radios = Radio::with('genres')->where('isActive', true)
            ->whereHas('genres', function ($query) use ($genre) {
                $query->where('genres.id', $genre->id);
            })->get();

        // Generar la URL canónica
        $canonical_url = route('ciudades.show', ['slug' => $genre->slug]);

        // Keywords dinámicas para la ciudad
        $keywords = [
            'emisoras '.$genre->name,
            'radios '.$genre->name,
            'radio online '.$genre->name,
            $genre->name.' República Dominicana',
            'escuchar radio '.$genre->name,
        ];

        $description = 'Encuentra y escucha las mejores emisoras de radio en '.$genre->name.', República Dominicana. Directorio actualizado con '.$radios->count().' emisoras en vivo.';

        $this->setSeoData(
            'Emisoras de Radio en '.$genre->name.' | Domiradios',
            $description,
            asset('img/domiradios-logo-og.jpg'),
            $keywords,
            $canonical_url
        );

        // Canonical URL
        SEOMeta::setCanonical($canonical_url);

        // JSON-LD Structured Data (Schema.org CollectionPage)
        JsonLd::setType('CollectionPage');
        JsonLd::setTitle('Emisoras de Radio en '.$genre->name);
        JsonLd::setDescription($description);
        JsonLd::setUrl($canonical_url);
        JsonLd::addImage(asset('img/domiradios-logo-og.jpg'));
        JsonLd::addValue('mainEntity', [
            '@type' => 'ItemList',
            'numberOfItems' => $radios->count(),
            'itemListElement' => $radios->take(10)->map(function ($radio, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'RadioStation',
                        'name' => $radio->name,
                        'url' => route('emisoras.show', $radio->slug),
                        'broadcastFrequency' => $radio->bitrate,
                    ],
                ];
            })->values()->toArray(),
        ]);

        return view('emisoras_por_ciudad', compact('radios', 'genre'));
    }

    // Método para mostrar todas las ciudades (géneros)
    public function indexCiudades()
    {
        // Obtener solo ciudades con conteo de emisoras (cache 1 hora)
        $genres = Cache::remember('cities_with_radio_count', 3600, function () {
            return Genre::cities()->withCount('radios')
                ->having('radios_count', '>', 0)
                ->orderBy('radios_count', 'desc')
                ->get();
        });

        // Generar la URL canónica
        $canonical_url = route('ciudades.index');

        // Keywords dinámicas
        $keywords = [
            'radios dominicanas por ciudad',
            'emisoras República Dominicana',
            'radio online ciudades',
            'directorio radios dominicanas',
        ];

        // Añadir primeras 10 ciudades a keywords
        foreach ($genres->take(10) as $genre) {
            $keywords[] = 'radios '.$genre->name;
        }

        $description = 'Emisoras de República Dominicana organizadas por ciudad. Explora radios de Santo Domingo, Santiago, Azua y más. Escucha gratis en vivo.';

        $this->setSeoData(
            'Emisoras de Radio por Ciudades de República Dominicana | Domiradios',
            $description,
            asset('img/domiradios-logo-og.jpg'),
            $keywords,
            $canonical_url
        );

        // Canonical URL
        SEOMeta::setCanonical($canonical_url);

        // JSON-LD Structured Data (Schema.org CollectionPage)
        JsonLd::setType('CollectionPage');
        JsonLd::setTitle('Emisoras de Radio por Ciudades');
        JsonLd::setDescription($description);
        JsonLd::setUrl($canonical_url);
        JsonLd::addImage(asset('img/domiradios-logo-og.jpg'));
        JsonLd::addValue('mainEntity', [
            '@type' => 'ItemList',
            'numberOfItems' => $genres->count(),
            'itemListElement' => $genres->take(20)->map(function ($genre, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'City',
                        'name' => $genre->name,
                        'url' => route('ciudades.show', $genre->slug),
                    ],
                ];
            })->values()->toArray(),
        ]);

        return view('ciudades', compact('genres'));
    }

    // Método para mostrar todas las emisoras
    public function index()
    {
        $emisoras = Radio::with('genres')->where('isActive', true)
            ->orderBy('isFeatured', 'desc')
            ->orderBy('name')
            ->get(); // Emisoras activas, destacadas primero

        // Obtener últimos 3 posts del blog para el widget
        $latestBlogPosts = BlogPost::published()
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Generar la URL canónica
        $canonical_url = route('emisoras.index');

        // Keywords dinámicas basadas en las ciudades y géneros más populares
        $topGenres = $emisoras->pluck('genres')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->take(10)
            ->toArray();

        $keywords = array_merge([
            'radios dominicanas',
            'emisoras República Dominicana',
            'radio online dominicana',
            'escuchar radio dominicana gratis',
            'radio en vivo RD',
            'estaciones de radio dominicanas',
        ], $topGenres);

        $description = 'Directorio completo de emisoras de radio de República Dominicana. Escucha en vivo música, noticias y deportes de '.$emisoras->count().' radios dominicanas gratis. ¡Sintoniza ahora!';

        $this->setSeoData(
            'Emisoras de Radio Dominicanas Online en Vivo | Domiradios',
            $description,
            asset('img/domiradios-logo-og.jpg'),
            $keywords,
            $canonical_url
        );

        // Canonical URL
        SEOMeta::setCanonical($canonical_url);

        // JSON-LD Structured Data (Schema.org WebSite + ItemList)
        JsonLd::setType('WebSite');
        JsonLd::setTitle('Domiradios - Emisoras de Radio Dominicanas');
        JsonLd::setDescription($description);
        JsonLd::setUrl($canonical_url);
        JsonLd::addImage(asset('img/domiradios-logo-og.jpg'));

        // SearchAction para Google
        JsonLd::addValue('potentialAction', [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => route('buscar').'?q={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ]);

        // Lista de emisoras destacadas
        $featuredRadios = $emisoras->where('isFeatured', true)->take(10);
        if ($featuredRadios->isNotEmpty()) {
            JsonLd::addValue('mainEntity', [
                '@type' => 'ItemList',
                'name' => 'Emisoras Destacadas',
                'numberOfItems' => $featuredRadios->count(),
                'itemListElement' => $featuredRadios->values()->map(function ($radio, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'item' => [
                            '@type' => 'RadioStation',
                            'name' => $radio->name,
                            'url' => route('emisoras.show', ['slug' => $radio->slug]),
                            'broadcastFrequency' => $radio->bitrate,
                            'image' => $radio->img ? asset('storage/'.$radio->img) : null,
                        ],
                    ];
                })->toArray(),
            ]);
        }

        return view('emisoras', compact('emisoras', 'latestBlogPosts'));
    }

    // Método para obtener la canción actual y oyentes
    public function getCurrentTrack($id)
    {
        $radio = Radio::findOrFail($id);

        $linkRadio = $radio->link_radio; // Enlace completo incluyendo puerto y mount point

        Log::debug('Processing radio ID '.$id.' source_radio '.$radio->source_radio);

        try {
            // Parsear el link_radio para extraer componentes
            $parsedUrl = parse_url($linkRadio);
            $scheme = $parsedUrl['scheme'] ?? 'http'; // Default to http
            $host = $parsedUrl['host'] ?? null;
            $portFromUrl = $parsedUrl['port'] ?? null; // Port explicitly in link_radio
            $path = $parsedUrl['path'] ?? '';

            if (! $host) {
                Log::error("Could not parse host from link_radio '{$linkRadio}' for radio ID {$id}.");
                throw new \Exception('Invalid link_radio: host missing.');
            }

            // Construir la URL base del servidor de streaming (scheme://host:port)
            $serverBaseUrl = $scheme.'://'.$host;
            if ($portFromUrl) {
                $serverBaseUrl .= ':'.$portFromUrl;
            }

            Log::debug("Radio ID {$id}: baseUrl='{$serverBaseUrl}' path='{$path}'");

            $currentTrack = self::DEFAULT_TRACK_INFO;
            $listeners = $this->getFictitiousListeners($id);
            $infoUrl = '';

            switch ($radio->source_radio) {
                case self::SOURCE_SONICPANEL:
                    $sonicPort = $portFromUrl;
                    if (! $sonicPort) {
                        if (preg_match('/\/(\d+)(?:\/|$)/', $path, $matches)) {
                            $sonicPort = $matches[1];
                        } else {
                            Log::warning("Radio ID {$id}: SonicPanel port could not be determined, using fallback.");
                            $currentTrack = $radio->name.' - En vivo';
                            break;
                        }
                    }
                    $infoUrl = $scheme.'://'.$host.'/cp/get_info.php?p='.$sonicPort;

                    try {
                        $response = Http::timeout(5)->get($infoUrl);
                        if ($response->successful()) {
                            $json = json_decode($response->body(), true);
                            if ($json !== null) {
                                $currentTrack = $json['title'] ?? self::DEFAULT_TRACK_INFO;
                                $listeners = $json['listeners'] ?? $this->getFictitiousListeners($id);
                            }
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Radio ID {$id}: SonicPanel stats unavailable: ".$e->getMessage());
                    }
                    break;

                case self::SOURCE_SHOUTCAST:
                    // Shoutcast stats: /stats?sid=1 (v2 XML) o /7.html (v1 HTML)
                    $infoUrl = $serverBaseUrl.'/stats?sid=1';

                    try {
                        $response = Http::timeout(5)->get($infoUrl);
                        if ($response->failed()) {
                            // Intentar parsear HTML de Shoutcast v1
                            if (strpos($response->body(), '<HTML>') !== false) {
                                if (preg_match('/Current Song: <\/font><\/td><td class=default><b>(.*?)<\/b><\/td>/i', $response->body(), $songMatches)) {
                                    $currentTrack = trim($songMatches[1]);
                                }
                                if (preg_match('/Current Listeners: <\/font><\/td><td class=default><b>(\d+)<\/b><\/td>/i', $response->body(), $listenerMatches)) {
                                    $listeners = (int) trim($listenerMatches[1]);
                                }
                                break;
                            }
                            // Stats no disponibles (proxy, CDN, etc) - fallback silencioso
                            break;
                        }
                        $data = $response->body();
                        libxml_use_internal_errors(true);
                        $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
                        if ($xml === false) {
                            libxml_clear_errors();
                            // Intentar parsear como HTML v1
                            if (preg_match('/Current Song: <\/font><\/td><td class=default><b>(.*?)<\/b><\/td>/i', $data, $songMatches)) {
                                $currentTrack = trim($songMatches[1]);
                            }
                            if (preg_match('/Current Listeners: <\/font><\/td><td class=default><b>(\d+)<\/b><\/td>/i', $data, $listenerMatches)) {
                                $listeners = (int) trim($listenerMatches[1]);
                            }
                        } else {
                            $currentTrack = isset($xml->SONGTITLE) ? (string) $xml->SONGTITLE : self::DEFAULT_TRACK_INFO;
                            $listeners = isset($xml->CURRENTLISTENERS) ? (int) $xml->CURRENTLISTENERS : $this->getFictitiousListeners($id);
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Radio ID {$id}: Shoutcast stats unavailable: ".$e->getMessage());
                    }
                    break;

                case self::SOURCE_ICECAST:
                    // Icecast stats: /status-json.xsl (JSON) con fallback a .xspf (XML)
                    $infoUrl = $serverBaseUrl.'/status-json.xsl';

                    try {
                        $response = Http::timeout(5)->get($infoUrl);
                        if ($response->successful()) {
                            $json = json_decode($response->body(), true);
                            if ($json !== null && isset($json['icestats'])) {
                                $icestats = $json['icestats'];
                                $source = null;
                                if (isset($icestats['source']) && is_array($icestats['source'])) {
                                    if (array_is_list($icestats['source'])) {
                                        // Múltiples sources - buscar la que coincida con el path
                                        if (! empty($path) && $path !== '/') {
                                            foreach ($icestats['source'] as $src) {
                                                if (isset($src['listenurl']) && strpos($src['listenurl'], $path) !== false) {
                                                    $source = $src;
                                                    break;
                                                }
                                            }
                                        }
                                        if (! $source && ! empty($icestats['source'])) {
                                            $source = $icestats['source'][0];
                                        }
                                    } else {
                                        $source = $icestats['source'];
                                    }
                                }

                                if ($source) {
                                    $currentTrack = $source['title'] ?? $source['song'] ?? self::DEFAULT_TRACK_INFO;
                                    $listeners = $source['listeners'] ?? $this->getFictitiousListeners($id);
                                    break;
                                }
                            }
                        }

                        // Fallback XSPF
                        $xspfUrl = $linkRadio.'.xspf';
                        $xspfResponse = Http::timeout(5)->get($xspfUrl);
                        if ($xspfResponse->successful()) {
                            libxml_use_internal_errors(true);
                            $xml = simplexml_load_string($xspfResponse->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
                            if ($xml !== false) {
                                $namespaces = $xml->getNamespaces(true);
                                $xml->registerXPathNamespace('x', $namespaces[''] ?? 'http://xspf.org/ns/0/');
                                $titleNode = $xml->xpath('//x:trackList/x:track/x:title');
                                $currentTrack = isset($titleNode[0]) ? (string) $titleNode[0] : self::DEFAULT_TRACK_INFO;
                                $annotationNode = $xml->xpath('//x:trackList/x:track/x:annotation');
                                if (isset($annotationNode[0])) {
                                    if (preg_match('/Current Listeners:\s*(\d+)/i', (string) $annotationNode[0], $listenerMatches)) {
                                        $listeners = (int) $listenerMatches[1];
                                    }
                                }
                            }
                            libxml_clear_errors();
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Radio ID {$id}: Icecast stats unavailable: ".$e->getMessage());
                    }
                    break;

                case self::SOURCE_AZURACAST:
                    // AzuraCast API: /api/nowplaying o /api/nowplaying/station_id
                    $infoUrl = $serverBaseUrl.'/api/nowplaying';

                    try {
                        $response = Http::timeout(5)->get($infoUrl);
                        if ($response->failed()) {
                            // Intentar con station_id del path (/radio/ID/stream)
                            if (preg_match('/\/radio\/([^\/]+)\//', $path, $pathMatches)) {
                                $specificInfoUrl = $serverBaseUrl.'/api/nowplaying/'.$pathMatches[1];
                                $response = Http::timeout(5)->get($specificInfoUrl);
                            }
                        }
                        if ($response->successful()) {
                            $json = json_decode($response->body(), true);
                            if ($json !== null) {
                                $currentTrack = $json['now_playing']['song']['title'] ?? $json['now_playing']['song']['text'] ?? self::DEFAULT_TRACK_INFO;
                                $listeners = $json['listeners']['current'] ?? $json['listeners']['total'] ?? $this->getFictitiousListeners($id);
                            }
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Radio ID {$id}: AzuraCast stats unavailable: ".$e->getMessage());
                    }
                    break;

                case self::SOURCE_JRMSTREAM:
                    // JRMStream usa WebRTC/Opus, no tiene endpoint HTTP de stats
                    $currentTrack = $radio->name.' - En vivo';
                    $listeners = $this->getFictitiousListeners($id);
                    break;

                case 'Other':
                case 'user_submitted':
                    // Fuentes sin endpoint de stats conocido - fallback genérico
                    $currentTrack = $radio->name.' - En vivo';
                    $listeners = $this->getFictitiousListeners($id);
                    break;

                default:
                    Log::warning("Radio ID {$id}: Tipo de fuente no reconocido: ".$radio->source_radio);
                    $currentTrack = $radio->name.' - En vivo';
                    $listeners = $this->getFictitiousListeners($id);
                    break;
            }

            return response()->json([
                'current_track' => $currentTrack,
                'listeners' => $listeners,
            ]);

        } catch (\Throwable $e) { // Catch Throwable for broader error catching including Http client errors
            Log::error("Radio ID {$id}: Exception in getCurrentTrack: ".$e->getMessage().' at '.$e->getFile().':'.$e->getLine());

            // Fallback to default information on any exception
            return response()->json([
                'current_track' => self::DEFAULT_TRACK_INFO,
                'listeners' => $this->getFictitiousListeners($id),
            ]);
        }
    }

    // Método para generar un número de oyentes ficticio y realista
    private function getFictitiousListeners($radioId)
    {
        // Utilizar caché para mantener el conteo entre solicitudes
        $cacheKey = 'listeners_'.$radioId;
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
        $validated = $request->validate([
            'radio_id' => 'required|integer|exists:radios,id',
        ]);

        // Registrar la visita
        Visita::create(['radio_id' => $validated['radio_id']]);

        return response()->json(['message' => 'Visita registrada']);
    }
}
