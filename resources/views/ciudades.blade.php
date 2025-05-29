@extends('layouts.app')

@section('title', 'Emisoras por Ciudades - Domiradios')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <nav class="text-sm mb-6 text-gray-600">
        @php
        $breadcrumbs = [
            ['name' => 'Inicio', 'url' => route('emisoras.index')],
            ['name' => 'Ciudades']
        ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </nav>
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100">
        <h1 class="text-3xl font-bold mb-3 text-gray-800 flex items-center">
            <span class="text-brand-blue mr-3"><i class="fas fa-map-marker-alt"></i></span>
            Emisoras Por Ciudades Dominicanas
        </h1>
        <div class="prose max-w-none text-gray-600 mb-8">
            <p>
                Las mejores <strong>emisoras por ciudades dominicanas</strong> y <strong>
                radios de República Dominicana</strong> por ciudad. Escucha en vivo
                tus emisoras favoritas en Santo Domingo, Santiago, La Romana y más.
                Disfruta de música, noticias y programas locales con nuestro listado completo de
                <strong>emisoras por ciudad en República Dominicana</strong>.
            </p>
        </div>

        <!-- Grid de ciudades (manejado por Livewire) -->
        @livewire('city-list', ['genres' => $genres])
    </div>
</div>
@endsection
