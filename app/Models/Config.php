<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_full_bg',
        'ui_top_chart',
        'ui_genre',
        'ui_favorite',
        'ui_themes',
        'ui_detail_genre',
        'ui_player',
        'ui_search',
        'app_type',
    ];
}

