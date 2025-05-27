<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Radio;

class SearchEmisoras extends Component
{
    public $query = '';
    
    // Actualizar la variable cuando cambia el input
    public function updatedQuery()
    {
        $this->resetValidation();
    }
    
    public function render()
    {
        return view('livewire.search-emisoras');
    }
    
    public function search()
    {
        // Validar que haya un término de búsqueda
        $this->validate([
            'query' => 'required|min:2'
        ], [
            'query.required' => 'Ingresa un término para buscar',
            'query.min' => 'El término de búsqueda debe tener al menos 2 caracteres'
        ]);
        
        // Redirigir a la página de búsqueda con el query
        return redirect()->route('buscar', ['q' => $this->query]);
    }
}
