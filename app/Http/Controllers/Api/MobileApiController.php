<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MobileApiController extends Controller
{
    private array $allowedMethods = ['getgenres', 'getradios', 'getthemes', 'getremoteconfigs'];

    public function handle(Request $request)
    {
        $method = strtolower(trim($request->input('method', '')));

        if (! in_array($method, $this->allowedMethods)) {
            return response()->json(['status' => 403, 'msg' => 'Method not allowed'], 200);
        }

        if (! $this->checkApiKey($request)) {
            return response()->json(['status' => 404, 'msg' => 'Invalid api key'], 200);
        }

        return match ($method) {
            'getgenres' => $this->getGenres(),
            'getradios' => $this->getRadios($request),
            'getthemes' => $this->getThemes($request),
            'getremoteconfigs' => $this->getRemoteConfigs(),
            default => response()->json(['status' => 404, 'msg' => 'Method not found'], 200),
        };
    }

    private function checkApiKey(Request $request): bool
    {
        $apiKey = $request->input('api_key');
        $configKey = config('mobile.api_key');
        if (! $apiKey || ! $configKey) {
            return false;
        }

        return hash_equals($configKey, $apiKey);
    }

    private function success(array $datas): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 200,
            'msg' => 'success',
            'datas' => $datas,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Build full URL for an image path stored in the database.
     */
    private function imageUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        // Already a full URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url('storage/' . $path);
    }

    private function getGenres(): \Illuminate\Http\JsonResponse
    {
        $genres = DB::table('genres')
            ->select('id', 'name', 'img')
            ->where('isActive', 1)
            ->orderByDesc('id')
            ->get()
            ->map(function ($genre) {
                $genre->img = $this->imageUrl($genre->img);
                return $genre;
            })
            ->toArray();

        return $this->success($genres);
    }

    private function getRadios(Request $request): \Illuminate\Http\JsonResponse
    {
        $radioId = (int) $request->input('radio_id', 0);

        if ($radioId > 0) {
            $radios = DB::table('radios as r')
                ->select('r.id', 'r.name', 'r.img', 'r.bitrate', 'r.tags', 'r.type_radio',
                    'r.source_radio', 'r.link_radio', 'r.user_agent_radio',
                    'r.url_facebook', 'r.url_twitter', 'r.url_instagram', 'r.url_website')
                ->where('r.id', $radioId)
                ->where('r.isActive', 1)
                ->get()
                ->map(function ($radio) {
                    $radio->img = $this->imageUrl($radio->img);
                    return $radio;
                })
                ->toArray();

            return $this->success($radios);
        }

        $offset = (int) $request->input('offset', -1);
        $limit = (int) $request->input('limit', -1);

        if ($offset < 0 || $limit <= 0) {
            return response()->json(['status' => 404, 'msg' => 'Invalid Parameter'], 200);
        }

        $query = DB::table('radios as r')
            ->select('r.id', 'r.name', 'r.img', 'r.bitrate', 'r.tags', 'r.type_radio',
                'r.source_radio', 'r.link_radio', 'r.user_agent_radio',
                'r.url_facebook', 'r.url_twitter', 'r.url_instagram', 'r.url_website');

        $genreId = (int) $request->input('genre_id', 0);
        if ($genreId > 0) {
            $query->join('radios_cat as rc', function ($join) use ($genreId) {
                $join->on('rc.radio_id', '=', 'r.id')
                    ->where('rc.genre_id', '=', $genreId);
            });
        }

        $query->where('r.isActive', 1);

        $isFeature = (int) $request->input('is_feature', 0);
        if ($isFeature === 1) {
            $query->where('r.isFeatured', 1);
        }

        $q = $request->input('q', '');
        if ($q !== '') {
            $q = urldecode($q);
            $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $q);
            $query->where(function ($sub) use ($escaped) {
                $sub->where('r.name', 'LIKE', "%{$escaped}%")
                    ->orWhere('r.tags', 'LIKE', "%{$escaped}%");
            });
        }

        $radios = $query->orderByDesc('r.id')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function ($radio) {
                $radio->img = $this->imageUrl($radio->img);
                return $radio;
            })
            ->toArray();

        return $this->success($radios);
    }

    private function getThemes(Request $request): \Illuminate\Http\JsonResponse
    {
        $appType = (int) $request->input('app_type', 0);

        if ($appType === 1) {
            $themes = DB::table('themes')
                ->select('id', 'name', 'img', 'grad_start_color', 'grad_end_color', 'grad_orientation')
                ->where('is_single_theme', 1)
                ->where('isActive', 1)
                ->get()
                ->map(function ($theme) {
                    $theme->img = $this->imageUrl($theme->img);
                    return $theme;
                })
                ->toArray();

            return $this->success($themes);
        }

        $offset = (int) $request->input('offset', -1);
        $limit = (int) $request->input('limit', -1);

        if ($offset < 0 || $limit <= 0) {
            return response()->json(['status' => 404, 'msg' => 'Invalid Parameter'], 200);
        }

        $themes = DB::table('themes')
            ->select('id', 'name', 'img', 'grad_start_color', 'grad_end_color', 'grad_orientation')
            ->where('isActive', 1)
            ->orderByDesc('id')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function ($theme) {
                $theme->img = $this->imageUrl($theme->img);
                return $theme;
            })
            ->toArray();

        return $this->success($themes);
    }

    private function getRemoteConfigs(): \Illuminate\Http\JsonResponse
    {
        $row = DB::table('configs')->first();

        if (! $row) {
            return $this->success([]);
        }

        return $this->success([$row]);
    }
}
