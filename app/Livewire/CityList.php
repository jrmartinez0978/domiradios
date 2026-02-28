<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection; // Usaremos Collection para facilitar el filtrado

class CityList extends Component
{
    public Collection $allGenres; // Colección de todos los géneros/ciudades
    public string $searchTerm = '';

    // Escuchador para el evento que vendrá del buscador
    protected $listeners = ['searchTermUpdated' => 'updateSearchTerm'];

    public function mount(Collection $genres)
    {
        $this->allGenres = $genres;
    }

    public function updateSearchTerm($term)
    {
        $this->searchTerm = $term;
    }

    public function render()
    {
        $filteredGenres = $this->allGenres;

        if (!empty($this->searchTerm)) {
            $filteredGenres = $this->allGenres->filter(function ($genre) {
                // Filtrar por nombre del género/ciudad
                return stripos($genre->name, $this->searchTerm) !== false;
            });
        }

        return view('livewire.city-list', [
            'genres' => $filteredGenres
        ]);
    }
}
