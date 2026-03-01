<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadioRating extends Model
{
    protected $fillable = [
        'radio_id',
        'device_id',
        'rating',
    ];

    public function radio()
    {
        return $this->belongsTo(Radio::class);
    }
}
