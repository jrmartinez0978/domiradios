@extends('layouts.dark')

@section('title', 'Emisoras de Radio en ' . $genre->name . ' Online Gratis - Escucha en Vivo ' . date('Y'))

@section('meta_description', 'Escucha todas las emisoras de radio de ' . $genre->name . ' en vivo GRATIS. ' . $radios->count() . ' estaciones online disponibles 24/7. Compatible con celular, PC y tablet.')

@section('meta_keywords', 'radio ' . strtolower($genre->name) . ', emisoras ' . strtolower($genre->name) . ', radio online ' . strtolower($genre->name) . ', escuchar radio ' . strtolower($genre->name) . ', emisoras dominicanas ' . strtolower($genre->name))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    @php
    $breadcrumbs = [
        ['name' => 'Inicio', 'url' => route('emisoras.index')],
        ['name' => 'Ciudades', 'url' => route('ciudades.index')],
        ['name' => $genre->name]
    ];
    @endphp
    <x-breadcrumbs :items="$breadcrumbs" />

    {{-- SEO Content Header --}}
    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 border border-surface-300 mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800 flex items-center">
            <span class="text-primary mr-3"><i class="fas fa-map-marker-alt"></i></span>
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

        <p class="text-lg text-gray-600 mb-4 leading-relaxed">
            {!! $content['intro'] !!}
        </p>

        {{-- TL;DR summary box for AI Overview --}}
        <div class="bg-primary-50 p-4 rounded-lg mb-6 border-l-4 border-primary">
            <p class="text-gray-700 font-medium">
                <i class="fas fa-info-circle text-primary mr-2"></i>
                {!! $content['highlight'] !!}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-4 text-sm">
            <div class="flex items-center text-gray-600">
                <i class="fas fa-broadcast-tower text-primary mr-2"></i>
                <span><strong>{{ $radios->count() }}</strong> emisoras disponibles</span>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-signal text-primary mr-2"></i>
                <span>Transmisión en vivo 24/7</span>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-mobile-alt text-primary mr-2"></i>
                <span>Compatible todos los dispositivos</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 border border-surface-300">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
            <span class="text-primary mr-3"><i class="fas fa-list"></i></span>
            Listado de Emisoras en {{ $genre->name }}
        </h2>

        @if($radios->isEmpty())
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <i class="fas fa-broadcast-tower text-2xl"></i>
                </div>
                <p class="text-gray-600 text-lg">No hay emisoras disponibles en esta ciudad.</p>
                <a href="{{ url('/') }}" class="inline-flex items-center mt-4 text-primary hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a todas las emisoras
                </a>
            </div>
        @else
            <!-- Grid de emisoras -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($radios as $radio)
                    <x-radio-card :radio="$radio" :loop="$loop" />
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

