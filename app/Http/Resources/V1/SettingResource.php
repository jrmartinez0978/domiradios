<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'app_name' => $this->app_name,
            'app_email' => $this->app_email,
            'app_copyright' => $this->app_copyright,
            'app_phone' => $this->app_phone,
            'app_website' => $this->app_website,
            'app_facebook' => $this->app_facebook,
            'app_twitter' => $this->app_twitter,
            'app_term_of_use' => $this->app_term_of_use,
            'app_privacy_policy' => $this->app_privacy_policy,
        ];
    }
}
