<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination; // Importar el trait
use App\Models\Radio;

class RadioIndex extends Component
{
    use WithPagination; // Usar el trait

    public $search = '';

    // Resetea la página al actualizar la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $radios = Radio::where('name', 'like', '%' . $this->search . '%')
                       ->paginate(10); // Paginación de 10 en 10

        return view('livewire.radio-index', compact('radios'));
    }
}



