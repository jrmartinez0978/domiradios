<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ConfigResource;
use App\Models\Config;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ConfigController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $config = Config::first();

        if (! $config) {
            return $this->error('Configuration not found.', 404);
        }

        return $this->success(new ConfigResource($config));
    }
}
