<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RadioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'img' => $this->img ? url(Storage::url($this->img)) : null,
            'img_optimized' => $this->optimized_logo_url,
            'bitrate' => $this->bitrate,
            'tags' => $this->tags,
            'description' => $this->description,
            'address' => $this->address,
            'type_radio' => $this->type_radio,
            'source_radio' => $this->source_radio,
            'link_radio' => $this->link_radio,
            'user_agent_radio' => $this->user_agent_radio,
            'url_facebook' => $this->url_facebook,
            'url_twitter' => $this->url_twitter,
            'url_instagram' => $this->url_instagram,
            'url_website' => $this->url_website,
            'is_featured' => (bool) $this->isFeatured,
            'rating' => (float) $this->rating,
            'genres' => GenreResource::collection($this->whenLoaded('genres')),
            'city' => $this->city,
        ];
    }
}
