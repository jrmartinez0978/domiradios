<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Genre;
use App\Models\Radio;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with summary stats.
     */
    public function index()
    {
        $stats = [
            'total_radios' => Radio::count(),
            'active_radios' => Radio::where('isActive', true)->count(),
            'total_genres' => Genre::count(),
            'total_blog_posts' => BlogPost::count(),
            'total_users' => User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
