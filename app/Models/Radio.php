<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tags',
        'bitrate',
        'img',
        'type_radio',
        'source_radio',
        'link_radio',
        'user_agent_radio',
        'url_facebook',
        'url_twitter',
        'url_instagram',
        'url_website',
        'isFeatured',
        'isActive',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'radios_cat', 'radio_id', 'genre_id');
    }
}
