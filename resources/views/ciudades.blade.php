@extends('layouts.dark')

@section('title', 'Emisoras de Radio por Ciudades de República Dominicana - Domiradios ' . date('Y'))

@section('meta_description', 'Encuentra emisoras de radio dominicanas por ciudad. Más de 30 estaciones en Santo Domingo, Azua y otras ciudades de RD.')

@section('meta_keywords', 'emisoras por ciudad, radio dominicana, emisoras Santo Domingo, radio Azua, emisoras online RD')

@section('hero')
<div class="relative overflow-hidden py-16 md:py-20 bg-primary">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-3">
            <span class="text-white">Ciudades con Emisoras de Radio</span>
        </h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto mb-6">Encuentra emisoras de tu ciudad o explora otras regiones</p>
        <div class="max-w-2xl mx-auto">
            @livewire('search-emisoras', ['searchMode' => 'localFilter', 'genres' => $genres])
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <nav class="text-sm mb-6">
        @php
        $breadcrumbs = [
            ['name' => 'Inicio', 'url' => route('emisoras.index')],
            ['name' => 'Ciudades']
        ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </nav>

    {{-- SEO Content --}}
    <section class="card p-6 md:p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-map-marker-alt text-primary mr-3"></i>
            Emisoras de Radio por Ciudades de República Dominicana
        </h2>
        <div class="text-gray-600 leading-relaxed space-y-3 mb-6">
            <p class="text-lg">
                <strong class="text-gray-800">Domiradios</strong> te presenta el directorio más completo de <strong class="text-gray-800">emisoras de radio dominicanas organizadas por ciudad</strong>.
                Encuentra fácilmente las estaciones de tu localidad o explora emisoras de otras regiones del país.
            </p>
            <p>
                Navegar por <strong class="text-gray-800">emisoras por ciudad</strong> te permite descubrir contenido local específico: noticias regionales, eventos comunitarios,
                música típica de cada zona y programación que conecta con la identidad de cada provincia.
            </p>
        </div>
        <div class="grid sm:grid-cols-3 gap-4 text-sm">
            <div class="flex items-center gap-3 text-gray-600 bg-surface-100 rounded-xl p-3">
                <i class="fas fa-city text-primary text-lg"></i>
                <span><strong class="text-gray-800">{{ $genres->count() }}</strong> ciudades/regiones</span>
            </div>
            <div class="flex items-center gap-3 text-gray-600 bg-surface-100 rounded-xl p-3">
                <i class="fas fa-broadcast-tower text-primary text-lg"></i>
                <span>30+ emisoras en total</span>
            </div>
            <div class="flex items-center gap-3 text-gray-600 bg-surface-100 rounded-xl p-3">
                <i class="fas fa-globe text-primary text-lg"></i>
                <span>Streaming gratis 24/7</span>
            </div>
        </div>
    </section>

    {{-- City List --}}
    <section class="card p-6 md:p-8 mb-8">
        <h2 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-list text-primary mr-3"></i>
            Selecciona tu Ciudad
        </h2>
        @livewire('city-list', ['genres' => $genres])
    </section>

    {{-- Featured Cities --}}
    <section class="card p-6 md:p-8">
        <h2 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-star text-amber-500 mr-3"></i>
            Ciudades con Mayor Oferta de Emisoras
        </h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-surface-100 border border-surface-300 rounded-xl p-5">
                <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-building text-primary mr-2"></i> Santo Domingo
                </h3>
                <p class="text-gray-600 text-sm mb-3">La capital concentra la mayor cantidad y diversidad de emisoras del país.</p>
                <ul class="text-sm text-gray-500 space-y-1">
                    <li><i class="fas fa-music text-primary mr-2 w-4 text-center"></i>Música urbana y reggaeton</li>
                    <li><i class="fas fa-newspaper text-primary mr-2 w-4 text-center"></i>Noticias y análisis</li>
                    <li><i class="fas fa-futbol text-primary mr-2 w-4 text-center"></i>Deportes en vivo</li>
                    <li><i class="fas fa-heart text-primary mr-2 w-4 text-center"></i>Baladas y romántica</li>
                </ul>
            </div>
            <div class="bg-surface-100 border border-surface-300 rounded-xl p-5">
                <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-mountain text-primary mr-2"></i> Azua
                </h3>
                <p class="text-gray-600 text-sm mb-3">Emisoras que preservan las tradiciones musicales dominicanas.</p>
                <ul class="text-sm text-gray-500 space-y-1">
                    <li><i class="fas fa-guitar text-primary mr-2 w-4 text-center"></i>Merengue típico y bachata</li>
                    <li><i class="fas fa-users text-primary mr-2 w-4 text-center"></i>Programación comunitaria</li>
                    <li><i class="fas fa-church text-primary mr-2 w-4 text-center"></i>Contenido cristiano</li>
                    <li><i class="fas fa-leaf text-primary mr-2 w-4 text-center"></i>Noticias regionales</li>
                </ul>
            </div>
        </div>
        <div class="mt-5 p-4 bg-surface-100 rounded-xl border border-surface-300">
            <p class="text-gray-500 text-sm">
                <i class="fas fa-lightbulb text-amber-500 mr-2"></i>
                <strong class="text-gray-600">Tip:</strong> Explora emisoras de diferentes ciudades para descubrir la riqueza musical de cada región dominicana.
            </p>
        </div>
    </section>
</div>
@endsection
