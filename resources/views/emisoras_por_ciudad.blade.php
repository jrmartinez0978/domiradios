@extends('layouts.app')

@section('title', 'Emisoras en ' . $genre->name . ' - Domiradios')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100">
        <h1 class="text-3xl font-bold mb-3 text-gray-800 flex items-center">
            <span class="text-brand-blue mr-3"><i class="fas fa-map-marker-alt"></i></span>
            Emisoras en {{ $genre->name }}
        </h1>
        <p class="text-gray-600 mb-8">Descubre todas las emisoras de radio disponibles en {{ $genre->name }}.</p>

        @if($radios->isEmpty())
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <i class="fas fa-broadcast-tower text-2xl"></i>
                </div>
                <p class="text-gray-600 text-lg">No hay emisoras disponibles en esta ciudad.</p>
                <a href="{{ url('/') }}" class="inline-flex items-center mt-4 text-brand-blue hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a todas las emisoras
                </a>
            </div>
        @else
            <!-- Grid de emisoras -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($radios as $radio)
                    <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 group">
                        <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" class="block">
                            <div class="h-32 flex items-center justify-center mb-3 overflow-hidden bg-gray-50 rounded-lg p-2">
                                <img src="{{ Storage::url($radio->img) }}" alt="{{ $radio->name }}" class="mx-auto max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <h3 class="text-center font-medium text-gray-800 group-hover:text-brand-red transition-colors">{{ $radio->name }}</h3>
                            
                            <div class="mt-4 flex justify-center">
                                <span class="inline-flex items-center justify-center bg-gradient-to-r from-brand-blue to-brand-red text-white py-2 px-4 rounded-lg group-hover:opacity-90 transition-opacity text-sm">
                                    <i class="fas fa-headphones mr-2"></i> Escuchar
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

