<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Radio;

class RadioIndex extends Component
{
    public $search; // Para la búsqueda en vivo

    public function render()
    {
        // Obtén las radios, y si hay una búsqueda, filtra por el nombre
        // $radios = Radio::where('name', 'like', '%' . $this->search . '%')->get();
        $radios = Radio::where('name', 'like', '%' . $this->search . '%')->paginate(6);


        return view('livewire.radio-index', [
            'radios' => $radios,
        ]);
    }
}

