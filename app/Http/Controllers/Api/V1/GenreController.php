<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\GenreResource;
use App\Models\Genre;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $genres = Genre::genres()
            ->where('isActive', true)
            ->withCount(['radios' => fn ($q) => $q->where('isActive', true)])
            ->having('radios_count', '>', 0)
            ->orderBy('name')
            ->get();

        return $this->success(GenreResource::collection($genres));
    }
}
