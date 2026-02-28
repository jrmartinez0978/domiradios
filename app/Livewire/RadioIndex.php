<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Radio;
use App\Traits\HasSeo; // Importar el trait

class RadioIndex extends Component
{
    use WithPagination;
    use HasSeo; // Usar el trait

    public $search = '';  // Variable para la búsqueda

    protected $paginationTheme = 'tailwind'; // Configuración para Tailwind CSS

    public function mount()
    {
        // Establecer los datos SEO para esta página
        $this->setSeoData(
            'Emisoras Dominicanas Online', // Título de la página
            'Escucha las mejores emisoras de radio de República Dominicana en vivo. Directorio actualizado de radios dominicanas online.', // Descripción
            asset('img/domiradios-logo-og.jpg') // Imagen OG optimizada para redes sociales (1200x630px)
        );
    }

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





