@extends('layouts.app')

@section('title', 'Emisoras por Ciudades - Domiradios')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
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

        <!-- Grid de ciudades -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($genres as $genre)
                <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 group">
                    <a href="{{ route('ciudades.show', ['slug' => $genre->slug]) }}" class="block">
                        <div class="h-32 flex items-center justify-center mb-3 overflow-hidden bg-gray-50 rounded-lg p-2">
                            <!-- Verificar si el género tiene una imagen, de lo contrario mostrar una imagen por defecto -->
                            <img src="{{ $genre->img ? Storage::url($genre->img) : asset('images/default-image.jpg') }}" 
                                alt="{{ $genre->name }}" 
                                class="mx-auto max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center font-medium text-gray-800 group-hover:text-brand-red transition-colors mb-3">{{ $genre->name }}</h3>
                        
                        <div class="mt-4 flex justify-center">
                            <span class="inline-flex items-center justify-center bg-gradient-to-r from-brand-blue to-brand-red text-white py-2 px-4 rounded-lg group-hover:opacity-90 transition-opacity text-sm">
                                <i class="fas fa-broadcast-tower mr-2"></i> Ver emisoras
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
