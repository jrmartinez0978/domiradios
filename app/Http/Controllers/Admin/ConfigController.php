<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Show the form for editing the config.
     */
    public function edit()
    {
        $config = Config::firstOrCreate([], [
            'is_full_bg' => 0,
            'ui_top_chart' => 1,
            'ui_genre' => 1,
            'ui_favorite' => 1,
            'ui_themes' => 2,
            'ui_detail_genre' => 1,
            'ui_player' => 1,
            'ui_search' => 1,
            'app_type' => 1,
        ]);

        return view('admin.configs.edit', compact('config'));
    }

    /**
     * Update the config.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'is_full_bg' => 'required|integer|in:0,1',
            'ui_top_chart' => 'required|integer|in:1,2,3,4,5,6',
            'ui_genre' => 'required|integer|in:1,2,3,4,5,6',
            'ui_favorite' => 'required|integer|in:1,2,3,4,5,6',
            'ui_themes' => 'required|integer|in:1,2,3,4,5,6',
            'ui_detail_genre' => 'required|integer|in:1,2,3,4,5,6',
            'ui_player' => 'required|integer|in:1,2,3,4,5,6',
            'ui_search' => 'required|integer|in:1,2,3,4,5,6',
            'app_type' => 'required|integer|in:1,2',
        ]);

        $config = Config::firstOrCreate();
        $config->fill($data);
        $config->save();

        return redirect()->route('admin.configs.edit')
            ->with('success', 'Configuracion actualizada correctamente.');
    }
}
