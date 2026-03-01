<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRadioRequest;
use App\Http\Requests\Admin\UpdateRadioRequest;
use App\Models\Genre;
use App\Models\Radio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RadioController extends Controller
{
    /**
     * Display a listing of radios with search and filters.
     */
    public function index(Request $request)
    {
        $query = Radio::query();

        // Search by name or tags
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->has('status') && $request->input('status') !== '') {
            $isActive = $request->input('status') === 'active';
            $query->where('isActive', $isActive);
        }

        // Filter by featured
        if ($request->has('featured') && $request->input('featured') !== '') {
            $isFeatured = $request->input('featured') === 'yes';
            $query->where('isFeatured', $isFeatured);
        }

        $radios = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.radios.index', compact('radios'));
    }

    /**
     * Show the form for creating a new radio.
     */
    public function create()
    {
        $genres = Genre::genres()->orderBy('name')->get();
        $cities = Genre::cities()->orderBy('name')->get();

        return view('admin.radios.create', compact('genres', 'cities'));
    }

    /**
     * Store a newly created radio.
     */
    public function store(StoreRadioRequest $request)
    {
        $data = $request->validated();

        // Handle slug auto-generation
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('radios_logos', 'public');
        }

        $radio = Radio::create($data);

        // Sync géneros musicales y ciudad
        $genreIds = $request->input('genres', []);
        $cityId = $request->input('city_id');
        $syncIds = array_filter(array_merge($genreIds, $cityId ? [$cityId] : []));
        $radio->genres()->sync($syncIds);

        return redirect()->route('admin.radios.index')
            ->with('success', 'Emisora creada correctamente.');
    }

    /**
     * Display the specified radio.
     */
    public function show(Radio $radio)
    {
        $radio->load('genres');

        return view('admin.radios.show', compact('radio'));
    }

    /**
     * Show the form for editing the specified radio.
     */
    public function edit(Radio $radio)
    {
        $radio->load('genres');
        $genres = Genre::genres()->orderBy('name')->get();
        $cities = Genre::cities()->orderBy('name')->get();

        return view('admin.radios.edit', compact('radio', 'genres', 'cities'));
    }

    /**
     * Update the specified radio.
     */
    public function update(UpdateRadioRequest $request, Radio $radio)
    {
        $data = $request->validated();

        // Handle slug auto-generation
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('radios_logos', 'public');
        }

        $radio->update($data);

        // Sync géneros musicales y ciudad
        $genreIds = $request->input('genres', []);
        $cityId = $request->input('city_id');
        $syncIds = array_filter(array_merge($genreIds, $cityId ? [$cityId] : []));
        $radio->genres()->sync($syncIds);

        return redirect()->route('admin.radios.index')
            ->with('success', 'Emisora actualizada correctamente.');
    }

    /**
     * Remove the specified radio.
     */
    public function destroy(Radio $radio)
    {
        $radio->delete();

        return redirect()->route('admin.radios.index')
            ->with('success', 'Emisora eliminada correctamente.');
    }

    /**
     * Handle bulk actions on radios.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:radios,id',
        ]);

        $ids = $request->input('ids');
        $action = $request->input('action');

        switch ($action) {
            case 'activate':
                Radio::whereIn('id', $ids)->update(['isActive' => true]);
                $message = count($ids).' radio(s) activated.';
                break;
            case 'deactivate':
                Radio::whereIn('id', $ids)->update(['isActive' => false]);
                $message = count($ids).' radio(s) deactivated.';
                break;
            case 'feature':
                Radio::whereIn('id', $ids)->update(['isFeatured' => true]);
                $message = count($ids).' radio(s) featured.';
                break;
            case 'unfeature':
                Radio::whereIn('id', $ids)->update(['isFeatured' => false]);
                $message = count($ids).' radio(s) unfeatured.';
                break;
            case 'delete':
                Radio::whereIn('id', $ids)->delete();
                $message = count($ids).' radio(s) deleted.';
                break;
            default:
                $message = 'Unknown action.';
        }

        return redirect()->route('admin.radios.index')
            ->with('success', $message);
    }
}
