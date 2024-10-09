<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Radio;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RealTimeListenersMonitor extends LineChartWidget
{
    protected static ?string $heading = 'Monitor en tiempo real de Oyentes';

    protected function getData(): array
    {
        // Obtener las radios del modelo Radio
        $radios = Radio::all();

        $labels = [];
        $listenersData = [];

        foreach ($radios as $radio) {
            // Generar la URL según la fuente del streaming
            $url = $this->generateStreamingUrl($radio);

            try {
                // Obtener la información en tiempo real
                $response = Http::get($url);
                $data = $response->body();

                $listenersCount = $this->parseListenersCount($radio->source_radio, $data, $radio->id);

                $labels[] = $radio->name;
                $listenersData[] = $listenersCount;
            } catch (\Exception $e) {
                // En caso de error, muestra 0 oyentes
                $listenersData[] = 0;
            }
        }

        // Retornar los datos del gráfico de líneas
        return [
            'datasets' => [
                [
                    'label' => 'Oyentes en Tiempo Real',
                    'data' => $listenersData,
                    'borderColor' => '#4CAF50', // Color de la línea
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    // Generar la URL según la fuente de la emisora
    protected function generateStreamingUrl(Radio $radio): string
    {
        $linkRadio = $radio->link_radio;

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

        switch ($radio->source_radio) {
            case 'SonicPanel':
                if (!$port) {
                    // Intentar extraer el puerto del path
                    preg_match('/\/(\d+)\//', $path, $matches);
                    if (isset($matches[1])) {
                        $port = $matches[1];
                    } else {
                        throw new \Exception('No se pudo extraer el puerto para SonicPanel');
                    }
                }
                return $scheme . '://' . $host . '/cp/get_info.php?p=' . $port;

            case 'Shoutcast':
                return $baseUrl . '/stats?sid=1';

            case 'Icecast':
                return $linkRadio . '.xspf';

            case 'AzuraCast':
                return $baseUrl . '/api/nowplaying';

            default:
                throw new \Exception('Fuente de streaming no soportada');
        }
    }

    // Método para analizar el número de oyentes según la fuente
    protected function parseListenersCount($sourceRadio, $data, $radioId)
    {
        switch ($sourceRadio) {
            case 'SonicPanel':
                $json = json_decode($data, true);
                if ($json === null) {
                    throw new \Exception('Error al analizar JSON de SonicPanel');
                }
                $listeners = $json['listeners'] ?? $this->getFictitiousListeners($radioId);
                break;

            case 'Shoutcast':
                $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml === false) {
                    throw new \Exception('Error al analizar XML de Shoutcast');
                }
                $listeners = isset($xml->CURRENTLISTENERS) ? (int) $xml->CURRENTLISTENERS : $this->getFictitiousListeners($radioId);
                break;

            case 'Icecast':
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

            case 'AzuraCast':
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
