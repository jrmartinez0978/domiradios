<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'app_email',
        'app_copyright',
        'app_phone',
        'app_website',
        'app_facebook',
        'app_twitter',
        'app_term_of_use',
        'app_privacy_policy',
    ];
}
