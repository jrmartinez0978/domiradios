<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SettingResource;
use App\Models\Setting;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $setting = Setting::first();

        if (! $setting) {
            return $this->error('Settings not found.', 404);
        }

        return $this->success(new SettingResource($setting));
    }
}
