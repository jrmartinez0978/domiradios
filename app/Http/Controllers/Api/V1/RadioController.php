<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RadioListResource;
use App\Http\Resources\V1\RadioResource;
use App\Models\Radio;
use App\Models\RadioRating;
use App\Models\Visita;
use App\Services\StreamInfoService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RadioController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly StreamInfoService $streamInfoService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'genre_id' => 'sometimes|integer|exists:genres,id',
            'city_id' => 'sometimes|integer|exists:genres,id',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $query = Radio::with('genres')
            ->where('isActive', true)
            ->orderBy('isFeatured', 'desc')
            ->orderBy('name');

        if ($request->filled('genre_id')) {
            $query->whereHas('genres', fn ($q) => $q->where('genres.id', $request->genre_id));
        }

        if ($request->filled('city_id')) {
            $query->whereHas('cityGenres', fn ($q) => $q->where('genres.id', $request->city_id));
        }

        $perPage = $request->input('per_page', 20);
        $radios = $query->paginate($perPage);

        return $this->paginated(RadioListResource::collection($radios));
    }

    public function featured(): JsonResponse
    {
        $radios = Radio::with('genres')
            ->where('isActive', true)
            ->where('isFeatured', true)
            ->orderBy('name')
            ->get();

        return $this->success(RadioListResource::collection($radios));
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:1|max:100',
        ]);

        $query = $request->input('q');
        $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $query);

        $radios = Radio::with('genres')
            ->where('isActive', true)
            ->where(function ($q) use ($escaped) {
                $q->where('name', 'like', "%{$escaped}%")
                    ->orWhere('bitrate', 'like', "%{$escaped}%")
                    ->orWhere('tags', 'like', "%{$escaped}%");
            })
            ->orderBy('isFeatured', 'desc')
            ->orderBy('name')
            ->get();

        return $this->success(RadioListResource::collection($radios));
    }

    public function favorites(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array|max:50',
            'ids.*' => 'integer|min:1',
        ]);

        $radios = Radio::with('genres')
            ->whereIn('id', $request->input('ids'))
            ->where('isActive', true)
            ->get();

        return $this->success(RadioListResource::collection($radios));
    }

    public function show(int $id): JsonResponse
    {
        $radio = Radio::with('genres')
            ->where('isActive', true)
            ->findOrFail($id);

        return $this->success(new RadioResource($radio));
    }

    public function currentTrack(int $id): JsonResponse
    {
        $radio = Radio::findOrFail($id);
        $trackInfo = $this->streamInfoService->getTrackInfo($radio);

        return $this->success($trackInfo);
    }

    public function registerPlay(Request $request, int $id): JsonResponse
    {
        Radio::findOrFail($id);

        Visita::create(['radio_id' => $id]);

        return $this->success(null, 'Play registered.');
    }

    public function rate(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'device_id' => 'required|string|max:64',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $radio = Radio::findOrFail($id);

        RadioRating::updateOrCreate(
            [
                'radio_id' => $id,
                'device_id' => $request->input('device_id'),
            ],
            [
                'rating' => $request->input('rating'),
            ]
        );

        // Update the average rating on the radio
        $avgRating = RadioRating::where('radio_id', $id)->avg('rating');
        $radio->update(['rating' => round($avgRating, 1)]);

        return $this->success([
            'rating' => (float) $request->input('rating'),
            'average_rating' => round($avgRating, 1),
        ], 'Rating saved.');
    }

    public function userRating(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'device_id' => 'required|string|max:64',
        ]);

        Radio::findOrFail($id);

        $rating = RadioRating::where('radio_id', $id)
            ->where('device_id', $request->input('device_id'))
            ->first();

        return $this->success([
            'rating' => $rating ? (int) $rating->rating : null,
        ]);
    }
}
