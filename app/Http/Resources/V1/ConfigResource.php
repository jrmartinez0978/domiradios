<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $fields = $this->resource->getFillable();
        $result = [];

        foreach ($fields as $field) {
            $result[$field] = (int) $this->resource->{$field};
        }

        return $result;
    }
}
