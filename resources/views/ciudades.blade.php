@extends('layouts.app')

@section('title', 'Emisoras de Radio por Ciudades de República Dominicana - Domiradios ' . date('Y'))

@section('meta_description', 'Encuentra emisoras de radio dominicanas por ciudad. Más de 30 estaciones en Santo Domingo, Azua y otras ciudades de RD. Escucha gratis música, noticias y deportes en vivo.')

@section('meta_keywords', 'emisoras por ciudad, radio dominicana, emisoras Santo Domingo, radio Azua, emisoras online RD, radio por ciudad República Dominicana')

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
    {{-- SEO Content Header --}}
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100 mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900 flex items-center">
            <span class="text-brand-blue mr-3"><i class="fas fa-map-marker-alt"></i></span>
            Emisoras de Radio por Ciudades de República Dominicana
        </h1>

        <div class="prose max-w-none text-gray-700 mb-6 space-y-4">
            <p class="text-lg leading-relaxed">
                <strong>Domiradios</strong> te presenta el directorio más completo de <strong>emisoras de radio dominicanas organizadas por ciudad</strong>.
                Encuentra fácilmente las estaciones de tu localidad o explora emisoras de otras regiones del país. Desde la capital Santo Domingo hasta
                ciudades como Azua, cada región tiene su propia identidad radiofónica que refleja su cultura, música y tradiciones.
            </p>

            <p class="leading-relaxed">
                Navegar por <strong>emisoras por ciudad</strong> te permite descubrir contenido local específico: noticias regionales, eventos comunitarios,
                música típica de cada zona y programación que conecta con la identidad de cada provincia. Ya sea que busques <strong>radio urbana en Santo Domingo</strong>,
                <strong>merengue típico del sur</strong>, o emisoras cristianas locales, nuestro índice por ciudades facilita tu búsqueda.
            </p>
        </div>

        <div class="bg-gradient-to-r from-brand-blue/10 to-brand-red/10 p-5 rounded-lg border-l-4 border-brand-blue mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
                <i class="fas fa-info-circle text-brand-blue mr-2"></i>
                ¿Por qué escuchar radio por ciudad?
            </h2>
            <ul class="space-y-2 text-gray-800">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-brand-blue mr-2 mt-1"></i>
                    <span><strong>Contenido local relevante</strong>: Noticias, eventos y programación de tu comunidad</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-brand-blue mr-2 mt-1"></i>
                    <span><strong>Identidad cultural regional</strong>: Cada ciudad tiene su propio estilo musical y tradiciones</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-brand-blue mr-2 mt-1"></i>
                    <span><strong>Conexión con tus raíces</strong>: Perfecta para dominicanos en el exterior que quieren mantenerse conectados</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-brand-blue mr-2 mt-1"></i>
                    <span><strong>Descubre nuevas emisoras</strong>: Explora estaciones de otras ciudades y amplía tus horizontes musicales</span>
                </li>
            </ul>
        </div>

        <div class="grid md:grid-cols-3 gap-4 text-sm border-t border-gray-200 pt-6">
            <div class="flex items-center text-gray-700">
                <i class="fas fa-city text-brand-blue text-xl mr-3"></i>
                <span><strong>{{ $genres->count() }}</strong> ciudades/regiones</span>
            </div>
            <div class="flex items-center text-gray-700">
                <i class="fas fa-broadcast-tower text-brand-blue text-xl mr-3"></i>
                <span>30+ emisoras en total</span>
            </div>
            <div class="flex items-center text-gray-700">
                <i class="fas fa-globe text-brand-blue text-xl mr-3"></i>
                <span>Streaming gratis 24/7</span>
            </div>
        </div>
    </div>

    {{-- Listado de Ciudades --}}
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 flex items-center">
            <span class="text-brand-red mr-3"><i class="fas fa-list"></i></span>
            Selecciona tu Ciudad
        </h2>

        <!-- Grid de ciudades (manejado por Livewire) -->
        @livewire('city-list', ['genres' => $genres])
    </div>

    {{-- Ciudades Destacadas --}}
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 flex items-center">
            <span class="text-brand-blue mr-3"><i class="fas fa-star"></i></span>
            Ciudades con Mayor Oferta de Emisoras
        </h2>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-building text-brand-blue mr-2"></i>
                    Santo Domingo
                </h3>
                <p class="text-gray-700 mb-3">
                    La capital concentra la mayor cantidad y diversidad de emisoras del país. Desde cadenas nacionales hasta
                    estaciones especializadas en música urbana, tropical, noticias y deportes.
                </p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><i class="fas fa-music text-brand-red mr-2"></i>Música urbana y reggaeton</li>
                    <li><i class="fas fa-newspaper text-brand-red mr-2"></i>Noticias y análisis político</li>
                    <li><i class="fas fa-futbol text-brand-red mr-2"></i>Deportes en vivo</li>
                    <li><i class="fas fa-heart text-brand-red mr-2"></i>Baladas y música romántica</li>
                </ul>
            </div>

            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-mountain text-brand-blue mr-2"></i>
                    Azua
                </h3>
                <p class="text-gray-700 mb-3">
                    La región sur tiene emisoras que preservan las tradiciones musicales dominicanas, con énfasis en merengue típico,
                    bachata tradicional y contenido comunitario.
                </p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><i class="fas fa-guitar text-brand-red mr-2"></i>Merengue típico y bachata</li>
                    <li><i class="fas fa-users text-brand-red mr-2"></i>Programación comunitaria</li>
                    <li><i class="fas fa-church text-brand-red mr-2"></i>Contenido religioso cristiano</li>
                    <li><i class="fas fa-leaf text-brand-red mr-2"></i>Noticias y cultura regional</li>
                </ul>
            </div>
        </div>

        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-gray-700 text-sm">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                <strong>Tip:</strong> Explora emisoras de diferentes ciudades para descubrir la riqueza musical y cultural de cada región dominicana.
                Muchas estaciones regionales ofrecen contenido único que no encontrarás en cadenas nacionales.
            </p>
        </div>
    </div>
</div>
@endsection
