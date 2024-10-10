<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $fillable = ['radio_id'];

    public function radio()
    {
        return $this->belongsTo(Radio::class);
    }
}

