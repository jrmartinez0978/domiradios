@extends('layouts.app')

@section('title', 'Emisoras de Radio en ' . $genre->name . ' Online Gratis - Escucha en Vivo ' . date('Y'))

@section('meta_description', 'Escucha todas las emisoras de radio de ' . $genre->name . ' en vivo GRATIS. ' . $radios->count() . ' estaciones online disponibles 24/7. Compatible con celular, PC y tablet.')

@section('meta_keywords', 'radio ' . strtolower($genre->name) . ', emisoras ' . strtolower($genre->name) . ', radio online ' . strtolower($genre->name) . ', escuchar radio ' . strtolower($genre->name) . ', emisoras dominicanas ' . strtolower($genre->name))

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    @php
    $breadcrumbs = [
        ['name' => 'Inicio', 'url' => route('emisoras.index')],
        ['name' => 'Ciudades', 'url' => route('ciudades.index')],
        ['name' => $genre->name]
    ];
    @endphp
    <x-breadcrumbs :items="$breadcrumbs" />

    {{-- SEO Content Header --}}
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100 mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900 flex items-center">
            <span class="text-brand-blue mr-3"><i class="fas fa-map-marker-alt"></i></span>
            Emisoras de Radio en {{ $genre->name }} Online Gratis
        </h1>

        @php
        // Contenido dinámico basado en la ciudad
        $cityContent = [
            'Santo Domingo' => [
                'intro' => 'Santo Domingo, la capital de República Dominicana, cuenta con las principales emisoras de radio del país. Desde música urbana hasta noticias, encuentra todas las estaciones capitalinas transmitiendo en vivo.',
                'highlight' => 'Como centro cultural y político del país, Santo Domingo alberga las emisoras más influyentes de República Dominicana, incluyendo cadenas nacionales y estaciones locales con programación variada.'
            ],
            'Azua' => [
                'intro' => 'Azua, provincia del sur dominicano, tiene emisoras locales que reflejan la cultura y tradiciones de la región. Escucha radio tropical, noticias locales y programación comunitaria.',
                'highlight' => 'Las emisoras de Azua mantienen viva la tradición musical del sur dominicano, con especial énfasis en merengue típico, bachata tradicional y cobertura de eventos regionales.'
            ],
            'Online' => [
                'intro' => 'Emisoras digitales que transmiten exclusivamente por internet, sin frecuencia FM/AM tradicional. Radio online pura para audiencias globales.',
                'highlight' => 'Las radios online ofrecen programación especializada sin restricciones geográficas, llegando a dominicanos en todo el mundo con contenido único y formatos innovadores.'
            ]
        ];

        $content = $cityContent[$genre->name] ?? [
            'intro' => 'Descubre todas las emisoras de radio de ' . $genre->name . ' transmitiendo en vivo por internet. Escucha gratis desde cualquier dispositivo.',
            'highlight' => 'Emisoras locales de ' . $genre->name . ' con programación que refleja la cultura y tradiciones de la región dominicana.'
        ];
        @endphp

        <p class="text-lg text-gray-700 mb-4 leading-relaxed">
            {!! $content['intro'] !!}
        </p>

        <div class="bg-gradient-to-r from-brand-blue/10 to-brand-red/10 p-4 rounded-lg mb-6 border-l-4 border-brand-blue">
            <p class="text-gray-800 font-medium">
                <i class="fas fa-info-circle text-brand-blue mr-2"></i>
                {!! $content['highlight'] !!}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-4 text-sm">
            <div class="flex items-center text-gray-700">
                <i class="fas fa-broadcast-tower text-brand-blue mr-2"></i>
                <span><strong>{{ $radios->count() }}</strong> emisoras disponibles</span>
            </div>
            <div class="flex items-center text-gray-700">
                <i class="fas fa-signal text-brand-blue mr-2"></i>
                <span>Transmisión en vivo 24/7</span>
            </div>
            <div class="flex items-center text-gray-700">
                <i class="fas fa-mobile-alt text-brand-blue mr-2"></i>
                <span>Compatible todos los dispositivos</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 flex items-center">
            <span class="text-brand-red mr-3"><i class="fas fa-list"></i></span>
            Listado de Emisoras en {{ $genre->name }}
        </h2>

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

