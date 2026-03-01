<?php

namespace App\Services;

use App\Models\Radio;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StreamInfoService
{
    private const DEFAULT_TRACK_INFO = 'Sin información';

    private const SOURCE_SONICPANEL = 'SonicPanel';

    private const SOURCE_SHOUTCAST = 'Shoutcast';

    private const SOURCE_ICECAST = 'Icecast';

    private const SOURCE_AZURACAST = 'AzuraCast';

    private const SOURCE_JRMSTREAM = 'JRMStream';

    public function getTrackInfo(Radio $radio): array
    {
        $linkRadio = $radio->link_radio;

        Log::debug('StreamInfoService: Processing radio ID '.$radio->id.' source_radio '.$radio->source_radio);

        try {
            $parsedUrl = parse_url($linkRadio);
            $scheme = $parsedUrl['scheme'] ?? 'http';
            $host = $parsedUrl['host'] ?? null;
            $portFromUrl = $parsedUrl['port'] ?? null;
            $path = $parsedUrl['path'] ?? '';

            if (! $host) {
                Log::error("Could not parse host from link_radio '{$linkRadio}' for radio ID {$radio->id}.");
                throw new \Exception('Invalid link_radio: host missing.');
            }

            $serverBaseUrl = $scheme.'://'.$host;
            if ($portFromUrl) {
                $serverBaseUrl .= ':'.$portFromUrl;
            }

            $currentTrack = self::DEFAULT_TRACK_INFO;
            $listeners = $this->getFictitiousListeners($radio->id);

            switch ($radio->source_radio) {
                case self::SOURCE_SONICPANEL:
                    [$currentTrack, $listeners] = $this->fetchSonicPanel($radio, $scheme, $host, $portFromUrl, $path, $listeners);
                    break;

                case self::SOURCE_SHOUTCAST:
                    [$currentTrack, $listeners] = $this->fetchShoutcast($radio, $serverBaseUrl, $listeners);
                    break;

                case self::SOURCE_ICECAST:
                    [$currentTrack, $listeners] = $this->fetchIcecast($radio, $serverBaseUrl, $linkRadio, $path, $listeners);
                    break;

                case self::SOURCE_AZURACAST:
                    [$currentTrack, $listeners] = $this->fetchAzuraCast($radio, $serverBaseUrl, $path, $listeners);
                    break;

                case self::SOURCE_JRMSTREAM:
                    $currentTrack = $radio->name.' - En vivo';
                    break;

                case 'Other':
                case 'user_submitted':
                    $currentTrack = $radio->name.' - En vivo';
                    break;

                default:
                    Log::warning("Radio ID {$radio->id}: Tipo de fuente no reconocido: ".$radio->source_radio);
                    $currentTrack = $radio->name.' - En vivo';
                    break;
            }

            return [
                'current_track' => $currentTrack,
                'listeners' => $listeners,
            ];

        } catch (\Throwable $e) {
            Log::error("Radio ID {$radio->id}: Exception in getTrackInfo: ".$e->getMessage().' at '.$e->getFile().':'.$e->getLine());

            return [
                'current_track' => self::DEFAULT_TRACK_INFO,
                'listeners' => $this->getFictitiousListeners($radio->id),
            ];
        }
    }

    public function getFictitiousListeners(int $radioId): int
    {
        $cacheKey = 'listeners_'.$radioId;
        $listeners = Cache::get($cacheKey, rand(1, 100));

        $change = rand(-3, 2);
        $listeners += $change;
        $listeners = max(1, min($listeners, 100));

        Cache::put($cacheKey, $listeners, now()->addMinutes(1));

        return $listeners;
    }

    private function fetchSonicPanel(Radio $radio, string $scheme, string $host, ?int $portFromUrl, string $path, int $defaultListeners): array
    {
        $currentTrack = self::DEFAULT_TRACK_INFO;
        $listeners = $defaultListeners;

        $sonicPort = $portFromUrl;
        if (! $sonicPort) {
            if (preg_match('/\/(\d+)(?:\/|$)/', $path, $matches)) {
                $sonicPort = $matches[1];
            } else {
                Log::warning("Radio ID {$radio->id}: SonicPanel port could not be determined, using fallback.");

                return [$radio->name.' - En vivo', $listeners];
            }
        }

        $infoUrl = $scheme.'://'.$host.'/cp/get_info.php?p='.$sonicPort;

        try {
            $response = Http::timeout(5)->get($infoUrl);
            if ($response->successful()) {
                $json = json_decode($response->body(), true);
                if ($json !== null) {
                    $currentTrack = $json['title'] ?? self::DEFAULT_TRACK_INFO;
                    $listeners = $json['listeners'] ?? $defaultListeners;
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Radio ID {$radio->id}: SonicPanel stats unavailable: ".$e->getMessage());
        }

        return [$currentTrack, $listeners];
    }

    private function fetchShoutcast(Radio $radio, string $serverBaseUrl, int $defaultListeners): array
    {
        $currentTrack = self::DEFAULT_TRACK_INFO;
        $listeners = $defaultListeners;

        $infoUrl = $serverBaseUrl.'/stats?sid=1';

        try {
            $response = Http::timeout(5)->get($infoUrl);
            if ($response->failed()) {
                if (strpos($response->body(), '<HTML>') !== false) {
                    [$currentTrack, $listeners] = $this->parseShoutcastV1Html($response->body(), $currentTrack, $listeners);
                }

                return [$currentTrack, $listeners];
            }

            $data = $response->body();
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($xml === false) {
                libxml_clear_errors();
                [$currentTrack, $listeners] = $this->parseShoutcastV1Html($data, $currentTrack, $listeners);
            } else {
                $currentTrack = isset($xml->SONGTITLE) ? (string) $xml->SONGTITLE : self::DEFAULT_TRACK_INFO;
                $listeners = isset($xml->CURRENTLISTENERS) ? (int) $xml->CURRENTLISTENERS : $defaultListeners;
            }
        } catch (\Throwable $e) {
            Log::warning("Radio ID {$radio->id}: Shoutcast stats unavailable: ".$e->getMessage());
        }

        return [$currentTrack, $listeners];
    }

    private function parseShoutcastV1Html(string $html, string $defaultTrack, int $defaultListeners): array
    {
        $currentTrack = $defaultTrack;
        $listeners = $defaultListeners;

        if (preg_match('/Current Song: <\/font><\/td><td class=default><b>(.*?)<\/b><\/td>/i', $html, $songMatches)) {
            $currentTrack = trim($songMatches[1]);
        }
        if (preg_match('/Current Listeners: <\/font><\/td><td class=default><b>(\d+)<\/b><\/td>/i', $html, $listenerMatches)) {
            $listeners = (int) trim($listenerMatches[1]);
        }

        return [$currentTrack, $listeners];
    }

    private function fetchIcecast(Radio $radio, string $serverBaseUrl, string $linkRadio, string $path, int $defaultListeners): array
    {
        $currentTrack = self::DEFAULT_TRACK_INFO;
        $listeners = $defaultListeners;

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
                        $listeners = $source['listeners'] ?? $defaultListeners;

                        return [$currentTrack, $listeners];
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
            Log::warning("Radio ID {$radio->id}: Icecast stats unavailable: ".$e->getMessage());
        }

        return [$currentTrack, $listeners];
    }

    private function fetchAzuraCast(Radio $radio, string $serverBaseUrl, string $path, int $defaultListeners): array
    {
        $currentTrack = self::DEFAULT_TRACK_INFO;
        $listeners = $defaultListeners;

        $infoUrl = $serverBaseUrl.'/api/nowplaying';

        try {
            $response = Http::timeout(5)->get($infoUrl);
            if ($response->failed()) {
                if (preg_match('/\/radio\/([^\/]+)\//', $path, $pathMatches)) {
                    $specificInfoUrl = $serverBaseUrl.'/api/nowplaying/'.$pathMatches[1];
                    $response = Http::timeout(5)->get($specificInfoUrl);
                }
            }
            if ($response->successful()) {
                $json = json_decode($response->body(), true);
                if ($json !== null) {
                    $currentTrack = $json['now_playing']['song']['title'] ?? $json['now_playing']['song']['text'] ?? self::DEFAULT_TRACK_INFO;
                    $listeners = $json['listeners']['current'] ?? $json['listeners']['total'] ?? $defaultListeners;
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Radio ID {$radio->id}: AzuraCast stats unavailable: ".$e->getMessage());
        }

        return [$currentTrack, $listeners];
    }
}
