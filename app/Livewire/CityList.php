<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class CityList extends Component
{
    public Collection $allGenres;

    public string $searchTerm = '';

    public function mount(Collection $genres)
    {
        $this->allGenres = $genres;
    }

    #[On('searchTermUpdated')]
    public function updateSearchTerm($term)
    {
        $this->searchTerm = $term;
    }

    public function render()
    {
        $filteredGenres = $this->allGenres;

        if (! empty($this->searchTerm)) {
            $filteredGenres = $this->allGenres->filter(function ($genre) {
                // Filtrar por nombre del género/ciudad
                return stripos($genre->name, $this->searchTerm) !== false;
            });
        }

        return view('livewire.city-list', [
            'genres' => $filteredGenres,
        ]);
    }
}
