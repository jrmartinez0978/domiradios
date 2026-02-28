<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show the form for editing the settings.
     */
    public function edit()
    {
        $setting = Setting::firstOrNew();

        return view('admin.settings.edit', compact('setting'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_email' => 'nullable|email|max:255',
            'app_copyright' => 'nullable|string|max:500',
            'app_phone' => 'nullable|string|max:50',
            'app_website' => 'nullable|url|max:255',
            'app_facebook' => 'nullable|url|max:255',
            'app_twitter' => 'nullable|url|max:255',
            'app_term_of_use' => 'nullable|string',
            'app_privacy_policy' => 'nullable|string',
        ]);

        $setting = Setting::firstOrNew();
        $setting->fill($data);
        $setting->save();

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Settings updated successfully.');
    }
}
