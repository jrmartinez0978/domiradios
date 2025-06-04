<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRadioSeoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'meta_title'       => 'required|string|max:70',
            'meta_description' => 'required|string|max:180',
            'og_title'         => 'nullable|string|max:70',
            'og_description'   => 'nullable|string|max:180',
            'og_image'         => 'nullable|url',
            'h1'               => 'nullable|string|max:80',
            'canonical_url'    => 'nullable|url',
        ];
    }
}
