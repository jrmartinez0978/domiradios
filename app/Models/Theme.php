<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img',
        'grad_start_color',
        'grad_end_color',
        'grad_orientation',
        'is_single_theme',
        'isActive',
    ];
}

