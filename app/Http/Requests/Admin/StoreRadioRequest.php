<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRadioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->user_status === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:radios,slug',
            'bitrate' => 'nullable|string|max:50',
            'link_radio' => 'required|url',
            'source_radio' => 'required|in:HTML5,RTCStream',
            'type_radio' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'isActive' => 'boolean',
            'isFeatured' => 'boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'user_agent_radio' => 'nullable|string|max:500',
            'url_facebook' => 'nullable|url|max:255',
            'url_twitter' => 'nullable|url|max:255',
            'url_instagram' => 'nullable|url|max:255',
            'url_website' => 'nullable|url|max:255',
        ];
    }
}
