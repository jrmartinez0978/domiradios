<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Radio;

class RadioIndex extends Component
{
    public $search = '';  // Variable para la búsqueda

    public function render()
    {
        // Filtrar emisoras por nombre utilizando LIKE
        $radios = Radio::where('name', 'like', '%' . $this->search . '%')->get();

        return view('livewire.radio-index', [
            'radios' => $radios,
        ]);
    }
}


