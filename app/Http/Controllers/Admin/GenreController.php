<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    /**
     * Display a listing of genres.
     */
    public function index()
    {
        $genres = Genre::orderBy('name')->paginate(20);

        return view('admin.genres.index', compact('genres'));
    }

    /**
     * Show the form for creating a new genre.
     */
    public function create()
    {
        return view('admin.genres.create');
    }

    /**
     * Store a newly created genre.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:genres,slug',
            'img' => 'nullable|image|max:2048',
            'isActive' => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('genres', 'public');
        }

        Genre::create($data);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre created successfully.');
    }

    /**
     * Display the specified genre.
     */
    public function show(Genre $genre)
    {
        $genre->load('radios');

        return view('admin.genres.show', compact('genre'));
    }

    /**
     * Show the form for editing the specified genre.
     */
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    /**
     * Update the specified genre.
     */
    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:genres,slug,'.$genre->id,
            'img' => 'nullable|image|max:2048',
            'isActive' => 'boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('genres', 'public');
        }

        $genre->update($data);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre updated successfully.');
    }

    /**
     * Remove the specified genre.
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre deleted successfully.');
    }
}
