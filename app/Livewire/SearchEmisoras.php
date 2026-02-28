<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class SearchEmisoras extends Component
{
    public $query = '';

    public $searchMode = 'global'; // 'global' o 'localFilter'

    public Collection $genres; // Colección de géneros/ciudades para el modo localFilter

    public function mount($searchMode = 'global', ?Collection $genres = null)
    {
        $this->searchMode = $searchMode;
        $this->genres = $genres ?? collect(); // Inicializar como colección vacía si es null
    }

    // Actualizar la variable cuando cambia el input
    public function updatedQuery()
    {
        $this->resetValidation();
        // Si estamos en modo local, emitir el evento en tiempo real mientras se escribe
        if ($this->searchMode === 'localFilter') {
            $this->dispatch('searchTermUpdated', $this->query);
        }
    }

    public function render()
    {
        return view('livewire.search-emisoras');
    }

    public function search()
    {
        // No validar aquí si es localFilter y el query está vacío,
        // ya que un query vacío en localFilter simplemente limpia el filtro en CityList.
        if ($this->searchMode === 'global' || ($this->searchMode === 'localFilter' && ! empty($this->query))) {
            $this->validate([
                'query' => 'required|min:2',
            ], [
                'query.required' => 'Ingresa un término para buscar',
                'query.min' => 'El término de búsqueda debe tener al menos 2 caracteres',
            ]);
        }

        if ($this->searchMode === 'localFilter') {
            if (! empty($this->query)) {
                // Intentar encontrar una coincidencia exacta para redirigir
                $normalizedQuery = Str::lower($this->query);
                $match = $this->genres->first(function ($genre) use ($normalizedQuery) {
                    return Str::lower($genre->name) === $normalizedQuery;
                });

                if ($match) {
                    return redirect()->route('ciudades.show', ['slug' => $match->slug]);
                }
            }
            // Si no hay redirección (sin query, o query sin coincidencia exacta),
            // asegurarse de que CityList esté actualizado.
            $this->dispatch('searchTermUpdated', $this->query);
        } else {
            // Comportamiento original: Redirigir a la página de búsqueda global
            return redirect()->route('buscar', ['q' => $this->query]);
        }
    }
}
