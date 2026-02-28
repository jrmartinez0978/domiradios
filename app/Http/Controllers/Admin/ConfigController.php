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
        $config = Config::firstOrNew();

        return view('admin.configs.edit', compact('config'));
    }

    /**
     * Update the config.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'is_full_bg' => 'boolean',
            'ui_top_chart' => 'boolean',
            'ui_genre' => 'boolean',
            'ui_favorite' => 'boolean',
            'ui_themes' => 'boolean',
            'ui_detail_genre' => 'boolean',
            'ui_player' => 'boolean',
            'ui_search' => 'boolean',
            'app_type' => 'nullable|string|max:50',
        ]);

        $config = Config::firstOrNew();
        $config->fill($data);
        $config->save();

        return redirect()->route('admin.configs.edit')
            ->with('success', 'Configuration updated successfully.');
    }
}
