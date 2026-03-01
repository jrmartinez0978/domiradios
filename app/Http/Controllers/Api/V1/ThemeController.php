<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ThemeResource;
use App\Models\Theme;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Theme::where('isActive', true);

        if ($request->has('single_theme')) {
            $query->where('is_single_theme', (bool) $request->input('single_theme'));
        }

        $themes = $query->orderBy('name')->get();

        return $this->success(ThemeResource::collection($themes));
    }
}
