<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioCat extends Model
{
    use HasFactory;

    protected $table = 'radios_cat';

    protected $fillable = [
        'radio_id',
        'genre_id',
    ];

    public function radio()
{
    return $this->belongsTo(Radio::class, 'radio_id', 'id');
}


public function genre()
{
    return $this->belongsTo(Genre::class, 'genre_id', 'id');
}

}

