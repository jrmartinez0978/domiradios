<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Genre;
use App\Models\Radio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRadios = Radio::count();
        $activeRadios = Radio::where('isActive', true)->count();
        $totalBlogPosts = BlogPost::count();
        $totalUsers = User::count();

        // Radios agrupadas por género (top 10)
        $radiosByCity = Genre::withCount('radios')
            ->orderByDesc('radios_count')
            ->limit(10)
            ->get()
            ->map(fn ($g) => ['label' => $g->name, 'value' => $g->radios_count]);

        // Reproducciones últimos 7 días (si existe la columna plays_count o tabla de logs)
        $playsByDay = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            return [
                'label' => $date->format('d/m'),
                'value' => 0, // Placeholder - adjust if play tracking table exists
            ];
        });

        return view('admin.dashboard', compact(
            'totalRadios', 'activeRadios', 'totalBlogPosts', 'totalUsers',
            'radiosByCity', 'playsByDay'
        ));
    }
}
