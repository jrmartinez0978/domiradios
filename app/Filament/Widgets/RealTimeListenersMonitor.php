<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Radio;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RealTimeListenersMonitor extends Widget
{
    protected ?string $heading = 'Monitor en tiempo real de Oyentes por Ciudad';


    protected static string $view = 'filament.widgets.real-time-listeners-monitor';

    public function getListenersData()
    {
        // Obtener todas las ciudades (géneros) únicas asociadas a las radios
        $cities = Radio::with('genres')->get()->pluck('genres')->flatten()->unique('id');

        $data = [];

        foreach ($cities as $city) {
            $cityName = $city->name;
            $data[$cityName] = [];

            $radiosInCity = Radio::whereHas('genres', function ($query) use ($city) {
                $query->where('genres.id', $city->id);
            })->get();

            foreach ($radiosInCity as $radio) {
                // Generar la URL según la fuente del streaming
                $url = $this->generateStreamingUrl($radio);

                // Si la URL es null, omitir esta radio
                if (is_null($url)) {
                    continue;
                }

                try {
                    // Obtener la información en tiempo real
                    $response = Http::get($url);
                    $dataResponse = $response->body();

                    $listenersCount = $this->parseListenersCount($radio->source_radio, $dataResponse, $radio->id);

                } catch (\Exception $e) {
                    // En caso de error, muestra 0 oyentes
                    $listenersCount = 0;
                }

                // Agregar la información de la emisora a la ciudad correspondiente
                $data[$cityName][] = [
                    'radio_name' => $radio->name,
                    'listeners' => $listenersCount,
                ];
            }
        }

        return $data;
    }

    // Los demás métodos permanecen iguales...
    // generateStreamingUrl(), parseListenersCount(), getFictitiousListeners()

    // Generar la URL según la fuente de la emisora
    protected function generateStreamingUrl(Radio $radio): ?string
    {
        $linkRadio = $radio->link_radio;

        // Validar datos básicos
        if (empty($linkRadio) || empty($radio->source_radio)) {
            // Si faltan datos esenciales, omitir esta radio
            return null;
        }

        // Parsear el linkRadio para extraer componentes
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

        // Normalizar el valor de source_radio
        $sourceRadio = strtolower(trim($radio->source_radio));

        switch ($sourceRadio) {
            case 'sonicpanel':
                if (!$port) {
                    // Intentar extraer el puerto del path
                    preg_match('/\/(\d+)\//', $path, $matches);
                    if (isset($matches[1])) {
                        $port = $matches[1];
                    } else {
                        // No se pudo extraer el puerto, omitir esta radio
                        return null;
                    }
                }
                return $scheme . '://' . $host . '/cp/get_info.php?p=' . $port;

            case 'shoutcast':
                return $baseUrl . '/stats?sid=1';

            case 'icecast':
                return $linkRadio . '.xspf';

            case 'azuracast':
                return $baseUrl . '/api/nowplaying';

            default:
                // Fuente de streaming no soportada, omitir esta radio
                return null;
        }
    }

    // Método para analizar el número de oyentes según la fuente
    protected function parseListenersCount($sourceRadio, $data, $radioId)
    {
        // Normalizar el valor de sourceRadio
        $sourceRadio = strtolower(trim($sourceRadio));

        switch ($sourceRadio) {
            case 'sonicpanel':
                $json = json_decode($data, true);
                if ($json === null) {
                    throw new \Exception('Error al analizar JSON de SonicPanel');
                }
                $listeners = $json['listeners'] ?? $this->getFictitiousListeners($radioId);
                break;

            case 'shoutcast':
                $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml === false) {
                    throw new \Exception('Error al analizar XML de Shoutcast');
                }
                $listeners = isset($xml->CURRENTLISTENERS) ? (int) $xml->CURRENTLISTENERS : $this->getFictitiousListeners($radioId);
                break;

            case 'icecast':
                $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml === false) {
                    throw new \Exception('Error al analizar XML de Icecast');
                }
                // Namespace de XSPF
                $namespaces = $xml->getNamespaces(true);
                $xml->registerXPathNamespace('x', $namespaces['']);

                // Extraer el número de oyentes desde el campo 'annotation'
                $annotationNode = $xml->xpath('//x:trackList/x:track/x:annotation');
                if (isset($annotationNode[0])) {
                    $annotationText = (string) $annotationNode[0];
                    // Buscar 'Current Listeners' en el texto de 'annotation'
                    if (preg_match('/Current Listeners:\s*(\d+)/i', $annotationText, $matches)) {
                        $listeners = (int) $matches[1];
                    } else {
                        $listeners = $this->getFictitiousListeners($radioId);
                    }
                } else {
                    $listeners = $this->getFictitiousListeners($radioId);
                }
                break;

            case 'azuracast':
                $json = json_decode($data, true);
                if ($json === null) {
                    throw new \Exception('Error al analizar JSON de AzuraCast');
                }
                $listeners = $json['listeners']['current'] ?? $this->getFictitiousListeners($radioId);
                break;

            default:
                $listeners = $this->getFictitiousListeners($radioId);
                break;
        }

        return $listeners;
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

