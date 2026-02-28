<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Display a listing of themes.
     */
    public function index()
    {
        $themes = Theme::orderBy('name')->paginate(20);

        return view('admin.themes.index', compact('themes'));
    }

    /**
     * Show the form for creating a new theme.
     */
    public function create()
    {
        return view('admin.themes.create');
    }

    /**
     * Store a newly created theme.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'nullable|image|max:2048',
            'grad_start_color' => 'nullable|string|max:20',
            'grad_end_color' => 'nullable|string|max:20',
            'grad_orientation' => 'nullable|string|max:50',
            'is_single_theme' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Map form field name to model attribute
        if (array_key_exists('is_active', $data)) {
            $data['isActive'] = $data['is_active'];
            unset($data['is_active']);
        }

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('themes', 'public');
        }

        Theme::create($data);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme created successfully.');
    }

    /**
     * Display the specified theme.
     */
    public function show(Theme $theme)
    {
        return view('admin.themes.show', compact('theme'));
    }

    /**
     * Show the form for editing the specified theme.
     */
    public function edit(Theme $theme)
    {
        return view('admin.themes.edit', compact('theme'));
    }

    /**
     * Update the specified theme.
     */
    public function update(Request $request, Theme $theme)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'nullable|image|max:2048',
            'grad_start_color' => 'nullable|string|max:20',
            'grad_end_color' => 'nullable|string|max:20',
            'grad_orientation' => 'nullable|string|max:50',
            'is_single_theme' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Map form field name to model attribute
        if (array_key_exists('is_active', $data)) {
            $data['isActive'] = $data['is_active'];
            unset($data['is_active']);
        }

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('themes', 'public');
        }

        $theme->update($data);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme updated successfully.');
    }

    /**
     * Remove the specified theme.
     */
    public function destroy(Theme $theme)
    {
        $theme->delete();

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme deleted successfully.');
    }
}
