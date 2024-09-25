@extends('layouts.default')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Ciudades</h1>

    <!-- Grid de ciudades -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-6">
        @foreach($genres as $genre)
            <div class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center">
                <a href="{{ route('ciudades.show', ['slug' => $genre->slug]) }}">
                    <!-- Verificar si el género tiene una imagen, de lo contrario mostrar una imagen por defecto -->
                    <img src="{{ $genre->img ? Storage::url($genre->img) : asset('images/default-image.jpg') }}"
                         alt="{{ $genre->name }}"
                         class="w-full h-32 object-cover rounded-md">
                </a>
                <h3 class="text-center text-xl font-bold mt-2">{{ $genre->name }}</h3>

                <!-- Botón elegante de "Ver emisoras" -->
                <a href="{{ route('ciudades.show', ['slug' => $genre->slug]) }}"
                   class="mt-4 bg-green-500 text-white px-6 py-2 rounded-full hover:bg-green-600 transition">
                    Ver emisoras
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection




