<?php

namespace App\Livewire;

use App\Models\Genre;
use App\Models\Radio;
use App\Traits\HasSeo;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class RadioIndex extends Component
{
    use HasSeo;
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    #[Url]
    public $genre = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->setSeoData(
            'Emisoras Dominicanas Online',
            'Escucha las mejores emisoras de radio de República Dominicana en vivo. Directorio actualizado de radios dominicanas online.',
            asset('img/domiradios-logo-og.jpg')
        );
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGenre()
    {
        $this->resetPage();
    }

    public function render()
    {
        $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $this->search);

        // Featured radios (only when no search/filter active)
        $featured = collect();
        if (empty($this->search) && empty($this->genre)) {
            $featured = Radio::with('genres')
                ->where('isActive', true)
                ->where('isFeatured', true)
                ->limit(6)
                ->get();
        }

        // All radios query
        $query = Radio::with('genres')
            ->where('isActive', true);

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $escaped . '%');
        }

        if (!empty($this->genre)) {
            $query->whereHas('genres', fn ($q) => $q->where('genres.id', $this->genre));
        }

        $radios = $query->orderByDesc('isFeatured')->paginate(15);

        $genres = Genre::genres()->withCount('radios')
            ->having('radios_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('livewire.radio-index', [
            'featured' => $featured,
            'radios' => $radios,
            'genres' => $genres,
        ]);
    }
}
