<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ThemeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'img' => $this->img ? url(Storage::url($this->img)) : null,
            'grad_start_color' => $this->grad_start_color,
            'grad_end_color' => $this->grad_end_color,
            'grad_orientation' => $this->grad_orientation,
            'is_single_theme' => (bool) $this->is_single_theme,
        ];
    }
}
