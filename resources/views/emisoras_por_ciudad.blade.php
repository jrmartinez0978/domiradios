@extends('layouts.default')

@section('title', 'Emisoras de ' . $genre->name)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Emisoras en {{ $genre->name }}</h1>

    <!-- Grid de emisoras -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-6">
        @foreach($radios as $radio)
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <!-- Imagen cuadrada de la emisora -->
                <div class="aspect-w-1 aspect-h-1">
                    <img src="{{ Storage::url($radio->img) }}" alt="{{ $radio->name }}" class="w-full h-full object-cover rounded-md">
                </div>
                <!-- Nombre de la emisora -->
                <h3 class="text-center text-lg font-bold mt-2">{{ $radio->name }}</h3>
                <!-- Enlace a los detalles de la emisora -->
                <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" class="block mt-2 text-blue-500 text-center hover:underline">
                    Escuchar
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
