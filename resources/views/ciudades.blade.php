@extends('layouts.dark')

@section('title', 'Emisoras de Radio por Ciudades de República Dominicana - Domiradios ' . date('Y'))

@section('meta_description', 'Encuentra emisoras de radio dominicanas por ciudad. Más de 30 estaciones en Santo Domingo, Azua y otras ciudades de RD.')

@section('meta_keywords', 'emisoras por ciudad, radio dominicana, emisoras Santo Domingo, radio Azua, emisoras online RD')

@section('hero')
<div class="relative overflow-hidden py-16 md:py-20">
    <div class="absolute inset-0 bg-gradient-to-br from-dark-950 via-dark-900 to-dark-950"></div>
    <div class="absolute inset-0 opacity-15">
        <div class="absolute top-0 right-0 w-1/3 h-1/2 bg-brand-blue/20 rounded-full blur-[100px]"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-3">
            <span class="text-gradient">Ciudades con Emisoras de Radio</span>
        </h1>
        <p class="text-dark-300 text-lg max-w-2xl mx-auto mb-6">Encuentra emisoras de tu ciudad o explora otras regiones</p>
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
    <section class="glass-card p-6 md:p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-100 mb-4 flex items-center">
            <i class="fas fa-map-marker-alt text-accent-red mr-3"></i>
            Emisoras de Radio por Ciudades de República Dominicana
        </h2>
        <div class="text-dark-300 leading-relaxed space-y-3 mb-6">
            <p class="text-lg">
                <strong class="text-gray-100">Domiradios</strong> te presenta el directorio más completo de <strong class="text-gray-200">emisoras de radio dominicanas organizadas por ciudad</strong>.
                Encuentra fácilmente las estaciones de tu localidad o explora emisoras de otras regiones del país.
            </p>
            <p>
                Navegar por <strong class="text-gray-200">emisoras por ciudad</strong> te permite descubrir contenido local específico: noticias regionales, eventos comunitarios,
                música típica de cada zona y programación que conecta con la identidad de cada provincia.
            </p>
        </div>
        <div class="grid sm:grid-cols-3 gap-4 text-sm">
            <div class="flex items-center gap-3 text-dark-300 bg-glass-white-10 rounded-xl p-3">
                <i class="fas fa-city text-accent-red text-lg"></i>
                <span><strong class="text-gray-200">{{ $genres->count() }}</strong> ciudades/regiones</span>
            </div>
            <div class="flex items-center gap-3 text-dark-300 bg-glass-white-10 rounded-xl p-3">
                <i class="fas fa-broadcast-tower text-accent-red text-lg"></i>
                <span>30+ emisoras en total</span>
            </div>
            <div class="flex items-center gap-3 text-dark-300 bg-glass-white-10 rounded-xl p-3">
                <i class="fas fa-globe text-accent-red text-lg"></i>
                <span>Streaming gratis 24/7</span>
            </div>
        </div>
    </section>

    {{-- City List --}}
    <section class="glass-card p-6 md:p-8 mb-8">
        <h2 class="text-xl font-bold mb-6 text-gray-100 flex items-center">
            <i class="fas fa-list text-accent-red mr-3"></i>
            Selecciona tu Ciudad
        </h2>
        @livewire('city-list', ['genres' => $genres])
    </section>

    {{-- Featured Cities --}}
    <section class="glass-card p-6 md:p-8">
        <h2 class="text-xl font-bold mb-6 text-gray-100 flex items-center">
            <i class="fas fa-star text-accent-amber mr-3"></i>
            Ciudades con Mayor Oferta de Emisoras
        </h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-glass-white-10 border border-glass-border rounded-xl p-5">
                <h3 class="text-lg font-bold text-gray-100 mb-2 flex items-center">
                    <i class="fas fa-building text-accent-red mr-2"></i> Santo Domingo
                </h3>
                <p class="text-dark-300 text-sm mb-3">La capital concentra la mayor cantidad y diversidad de emisoras del país.</p>
                <ul class="text-sm text-dark-400 space-y-1">
                    <li><i class="fas fa-music text-accent-red mr-2 w-4 text-center"></i>Música urbana y reggaeton</li>
                    <li><i class="fas fa-newspaper text-accent-red mr-2 w-4 text-center"></i>Noticias y análisis</li>
                    <li><i class="fas fa-futbol text-accent-red mr-2 w-4 text-center"></i>Deportes en vivo</li>
                    <li><i class="fas fa-heart text-accent-red mr-2 w-4 text-center"></i>Baladas y romántica</li>
                </ul>
            </div>
            <div class="bg-glass-white-10 border border-glass-border rounded-xl p-5">
                <h3 class="text-lg font-bold text-gray-100 mb-2 flex items-center">
                    <i class="fas fa-mountain text-accent-red mr-2"></i> Azua
                </h3>
                <p class="text-dark-300 text-sm mb-3">Emisoras que preservan las tradiciones musicales dominicanas.</p>
                <ul class="text-sm text-dark-400 space-y-1">
                    <li><i class="fas fa-guitar text-accent-red mr-2 w-4 text-center"></i>Merengue típico y bachata</li>
                    <li><i class="fas fa-users text-accent-red mr-2 w-4 text-center"></i>Programación comunitaria</li>
                    <li><i class="fas fa-church text-accent-red mr-2 w-4 text-center"></i>Contenido cristiano</li>
                    <li><i class="fas fa-leaf text-accent-red mr-2 w-4 text-center"></i>Noticias regionales</li>
                </ul>
            </div>
        </div>
        <div class="mt-5 p-4 bg-glass-white-10 rounded-xl border border-glass-border">
            <p class="text-dark-400 text-sm">
                <i class="fas fa-lightbulb text-accent-amber mr-2"></i>
                <strong class="text-dark-300">Tip:</strong> Explora emisoras de diferentes ciudades para descubrir la riqueza musical de cada región dominicana.
            </p>
        </div>
    </section>
</div>
@endsection
