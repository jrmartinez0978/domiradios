<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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

}
