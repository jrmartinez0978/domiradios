<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'img',
        'isActive',
    ];

    public function radios()

    {
        return $this->belongsToMany(Radio::class, 'radios_cat', 'genre_id', 'radio_id');
    }

    public function radioCats()
    {
        return $this->hasMany(RadioCat::class, 'genre_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($genre) {
            // Genera automáticamente el slug a partir del nombre si no se ha definido
            if (empty($genre->slug)) {
                $genre->slug = Str::slug($genre->name);
            }
        });
    }

}
