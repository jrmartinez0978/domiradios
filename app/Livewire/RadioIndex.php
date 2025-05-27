<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Radio;

class RadioIndex extends Component
{
    use WithPagination;

    public $search = '';  // Variable para la búsqueda

    protected $paginationTheme = 'tailwind'; // Configuración para Tailwind CSS

    // Reseteamos la página cuando cambia el término de búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Filtrar emisoras activas por nombre utilizando LIKE y paginar
        $radios = Radio::where('isActive', true)
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate(15); // Ajusta el número de elementos por página según tus necesidades

        return view('livewire.radio-index', [
            'radios' => $radios,
        ]);
    }
}





