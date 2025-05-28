<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Radio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
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
        'rating',          // Campo para la clasificación
        'description',     // Campo para la descripción
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($radio) {
            // Genera automáticamente el slug a partir del nombre si no se ha definido
            if (empty($radio->slug)) {
                $radio->slug = Str::slug($radio->name);
            }
        });
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'radios_cat', 'radio_id', 'genre_id');
    }
    
    /**
     * Accessor para obtener la descripción usando el nombre en español
     */
    public function getDescripcionAttribute()
    {
        return $this->attributes['description'] ?? null;
    }
    
    /**
     * Mutator para guardar la descripción usando el nombre en español
     */
    public function setDescripcionAttribute($value)
    {
        $this->attributes['description'] = $value;
    }
    
    /**
     * Accessor para leer el campo description garantizando que tenga el valor correcto
     */
    public function getDescriptionAttribute($value)
    {
        return $value;
    }
}

