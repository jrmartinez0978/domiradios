@extends('layouts.dark')

@if(isset($staticSeoHtml) && $staticSeoHtml)
    @section('static_seo_tags')
        {!! $staticSeoHtml !!}
    @endsection
@else

@section('title', $radio->name.' - Escucha en vivo '.$radio->bitrate.' - Domiradios')

@section('meta_description', 'Escucha ' . $radio->name . ' en vivo por internet. Emisora de radio ' . $radio->bitrate . ' - ' . Str::of($radio->tags)->explode(',')->first() . '. Transmisión online desde República Dominicana.')

@section('meta_keywords', $radio->name . ', ' . $radio->bitrate . ', radio online, emisora dominicana, ' . $radio->tags . ', radio en vivo, escuchar radio')

@section('head_additional')
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $radio->name }} - Escucha en vivo {{ $radio->bitrate }}">
<meta property="og:description" content="Escucha {{ $radio->name }} en vivo. Emisora {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}.">
<meta property="og:image" content="{{ $radio->optimized_logo_url }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="{{ $radio->name }} - Escucha en vivo {{ $radio->bitrate }}">
<meta property="twitter:description" content="Escucha {{ $radio->name }} en vivo. Emisora {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}.">
<meta property="twitter:image" content="{{ $radio->optimized_logo_url }}">

@php
    $schemaData = [
        '@context' => 'https://schema.org',
        '@type' => 'RadioStation',
        'name' => $radio->name,
        'url' => url()->current(),
        'logo' => $radio->optimized_logo_url,
        'image' => $radio->optimized_logo_url,
        'description' => "Escucha {$radio->name} en vivo. Emisora de radio {$radio->bitrate} - " . Str::of($radio->tags)->explode(',')->first() . ".",
        'genre' => $radio->tags,
        'frequency' => $radio->bitrate,
    ];
    if (!empty($radio->address)) {
        $schemaData['address'] = ['@type' => 'PostalAddress', 'streetAddress' => $radio->address, 'addressCountry' => 'República Dominicana'];
    }
    $sameAs = array_filter([$radio->url_website, $radio->url_facebook, $radio->url_twitter, $radio->url_instagram]);
    if (count($sameAs)) $schemaData['sameAs'] = array_values($sameAs);
    if (!empty($radio->rating)) {
        $schemaData['aggregateRating'] = ['@type' => 'AggregateRating', 'ratingValue' => $radio->rating, 'bestRating' => '5', 'worstRating' => '1', 'ratingCount' => '1'];
    }
    if (!empty($radio->link_radio)) {
        $schemaData['audio'] = ['@type' => 'AudioObject', 'contentUrl' => $radio->link_radio, 'encodingFormat' => $radio->type_radio];
        $schemaData['broadcastService'] = ['@type' => 'RadioBroadcastService', 'broadcastDisplayName' => $radio->name, 'broadcastFrequency' => ['@type' => 'BroadcastFrequencySpecification', 'broadcastFrequencyValue' => $radio->bitrate]];
    }
    if (empty($radio->address)) {
        $schemaData['contentLocation'] = ['@type' => 'Place', 'name' => $radio->genres->pluck('name')->implode(', ') . ', República Dominicana'];
    }
    $schemaData['speakable'] = ['@type' => 'SpeakableSpecification', 'cssSelector' => ['h1', '.radio-description']];
@endphp
<script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endsection
@endif

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 pt-6">
    {{-- Breadcrumbs --}}
    <nav class="text-sm mb-6" aria-label="Breadcrumb">
        @php
        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => route('emisoras.index')],
            ['name' => 'Ciudades', 'url' => route('ciudades.index')],
        ];
        if ($radio->genres->count() > 0) {
            $firstGenre = $radio->genres->first();
            if ($firstGenre) {
                $breadcrumbItems[] = ['name' => $firstGenre->name, 'url' => route('ciudades.show', $firstGenre->slug)];
            }
        }
        $breadcrumbItems[] = ['name' => $radio->name];
        @endphp
        <x-breadcrumbs :items="$breadcrumbItems" />
    </nav>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 pb-8">
    {{-- TL;DR box for AI Overview --}}
    <div class="glass-card p-4 mb-6 border-l-4 border-accent-red">
        <p class="text-dark-300 text-sm radio-description">
            <strong class="text-gray-100">{{ $radio->name }}</strong> es una emisora de radio dominicana que transmite en {{ $radio->bitrate }} desde {{ $radio->genres->pluck('name')->implode(', ') }}, República Dominicana. Escucha en vivo {{ Str::of($radio->tags)->explode(',')->first() }} y más géneros. Transmisión online gratuita 24/7.
        </p>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="md:flex">
            {{-- Radio Logo --}}
            <div class="md:w-1/3 p-6 flex items-center justify-center bg-glass-white-10">
                <img src="{{ $radio->optimized_logo_url }}" alt="{{ $radio->name }}" class="max-w-full h-auto max-h-56 rounded-xl" width="300" height="300" fetchpriority="high">
            </div>

            {{-- Radio Info --}}
            <div class="md:w-2/3 p-6 md:p-8">
                <h1 class="text-3xl font-bold mb-4 text-gray-100">{{ $radio->name }}</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="space-y-3">
                        <p class="text-dark-300 flex items-center">
                            <i class="fas fa-broadcast-tower text-accent-red w-6"></i>
                            <span class="font-semibold text-gray-200 mr-2">Frecuencia:</span> {{ $radio->bitrate }}
                        </p>
                        <p class="text-dark-300 flex items-center">
                            <i class="fas fa-music text-accent-red w-6"></i>
                            <span class="font-semibold text-gray-200 mr-2">Género:</span> {{ $radio->tags }}
                        </p>
                    </div>
                    <div class="space-y-3">
                        <p class="text-dark-300 flex items-center">
                            <i class="fas fa-map-marker-alt text-accent-red w-6"></i>
                            <span class="font-semibold text-gray-200 mr-2">Ciudad:</span> {{ $radio->genres->pluck('name')->implode(', ') }}
                        </p>
                        <p class="text-dark-300 flex items-center">
                            <i class="fas fa-road text-accent-red w-6"></i>
                            <span class="font-semibold text-gray-200 mr-2">Dirección:</span>
                            {{ !empty($radio->address) ? $radio->address : 'No disponible' }}
                        </p>
                        <p class="text-dark-300 flex items-center">
                            <i class="fas fa-flag text-accent-red w-6"></i>
                            <span class="font-semibold text-gray-200 mr-2">País:</span> República Dominicana
                        </p>
                    </div>
                </div>

                {{-- Social links --}}
                <div class="flex flex-wrap gap-3 mb-6">
                    @if($radio->url_website)
                    <a href="{{ $radio->url_website }}" target="_blank" rel="noopener noreferrer" class="btn-glass text-sm !px-3 !py-1.5">
                        <i class="fas fa-globe mr-1.5"></i>Web
                    </a>
                    @endif
                    @if($radio->url_facebook)
                    <a href="{{ $radio->url_facebook }}" target="_blank" rel="noopener noreferrer" class="btn-glass text-sm !px-3 !py-1.5">
                        <i class="fab fa-facebook-f mr-1.5"></i>Facebook
                    </a>
                    @endif
                    @if($radio->url_twitter)
                    <a href="{{ $radio->url_twitter }}" target="_blank" rel="noopener noreferrer" class="btn-glass text-sm !px-3 !py-1.5">
                        <i class="fab fa-twitter mr-1.5"></i>Twitter
                    </a>
                    @endif
                    @if($radio->url_instagram)
                    <a href="{{ $radio->url_instagram }}" target="_blank" rel="noopener noreferrer" class="btn-glass text-sm !px-3 !py-1.5">
                        <i class="fab fa-instagram mr-1.5"></i>Instagram
                    </a>
                    @endif
                </div>

                {{-- Live info + rating --}}
                <div class="bg-glass-white-10 border border-glass-border rounded-xl p-5">
                    <div class="mb-3">
                        <h3 class="font-semibold text-gray-200 flex items-center text-sm">
                            <i class="fas fa-music text-accent-red mr-2"></i>En directo ahora:
                        </h3>
                        <div class="text-lg font-medium text-gray-100 mt-1" id="current-track">Cargando...</div>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center text-dark-300 text-sm" id="listeners">
                                <i class="fas fa-headphones text-accent-red mr-2"></i> Oyentes: 0
                            </div>
                            {{-- Rating --}}
                            <div class="bg-glass-white-10 p-3 rounded-lg border border-glass-border">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="user-rating cursor-pointer" data-radio-id="{{ $radio->id }}" data-current-rating="{{ $radio->rating }}">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star rating-star text-xl {{ $i <= $radio->rating ? 'text-yellow-400' : 'text-dark-500' }} hover:text-yellow-400 transition-colors cursor-pointer" data-rating="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-dark-500">{{ number_format($radio->rating, 1) }}/5</span>
                                </div>
                            </div>
                        </div>

                        <button id="fav-btn" class="btn-glass text-sm flex items-center gap-2">
                            <i class="fas fa-heart"></i> <span>Favorito</span>
                        </button>
                    </div>
                </div>

                {{-- Play button (triggers global player) --}}
                @php
                    $detailPlayData = [
                        'id' => $radio->id,
                        'name' => $radio->name,
                        'image' => Storage::url($radio->img),
                        'slug' => $radio->slug,
                        'frequency' => $radio->bitrate,
                        'streamUrl' => $radio->link_radio,
                        'sourceRadio' => $radio->source_radio,
                    ];
                    if ($radio->source_radio === 'RTCStream') {
                        $pu = parse_url($radio->link_radio);
                        $detailPlayData['rtcSlug'] = end(array_filter(explode('/', $pu['path'] ?? ''))) ?: $radio->slug;
                        $detailPlayData['rtcServer'] = $pu['host'] ?? 'live.rtcstreaming.com';
                        $detailPlayData['rtcPort'] = $pu['port'] ?? 9000;
                    }
                @endphp
                <div class="mt-4" x-data>
                    <button
                        @click="$store.player.play({{ Js::from($detailPlayData) }})"
                        class="w-full btn-primary !py-4 text-lg flex items-center justify-center gap-2"
                    >
                        <template x-if="$store.player.radioId == {{ $radio->id }} && $store.player.isPlaying">
                            <span class="flex items-center gap-2">
                                <div class="flex items-end gap-[2px] h-4">
                                    <span class="equalizer-bar animate-equalizer-1"></span>
                                    <span class="equalizer-bar animate-equalizer-2"></span>
                                    <span class="equalizer-bar animate-equalizer-3"></span>
                                </div>
                                Reproduciendo
                            </span>
                        </template>
                        <template x-if="$store.player.radioId == {{ $radio->id }} && $store.player.isConnecting">
                            <span><i class="fas fa-circle-notch fa-spin mr-2"></i>Conectando...</span>
                        </template>
                        <template x-if="!($store.player.radioId == {{ $radio->id }} && ($store.player.isPlaying || $store.player.isConnecting))">
                            <span><i class="fas fa-play mr-2"></i>Escuchar en vivo</span>
                        </template>
                    </button>
                    @if($radio->source_radio === 'RTCStream')
                    <div class="text-center mt-1.5 text-xs text-accent-red/70 font-semibold">
                        <i class="fas fa-bolt mr-0.5"></i> Baja latencia con RTCStream
                    </div>
                    @endif
                </div>

                {{-- Description --}}
                @if($radio->description)
                <div class="mt-6 pt-6 border-t border-glass-border">
                    <h2 class="text-lg font-bold text-gray-200 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-accent-red mr-2"></i>Acerca de esta emisora
                    </h2>
                    <div class="prose prose-invert max-w-none text-dark-300 text-sm radio-description">{!! clean($radio->description) !!}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Related radios --}}
        <div class="p-6 md:p-8 border-t border-glass-border">
            <h2 class="text-lg font-bold mb-4 text-gray-100 flex items-center">
                <i class="fas fa-broadcast-tower text-accent-red mr-2"></i>
                Otras emisoras de {{ $radio->genres->pluck('name')->implode(', ') }}
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($related as $relatedRadio)
                @if(is_object($relatedRadio) && isset($relatedRadio->slug))
                <a href="{{ route('emisoras.show', ['slug' => $relatedRadio->slug]) }}" class="glass-card-hover group p-4 block">
                    <div class="aspect-square flex items-center justify-center mb-2 overflow-hidden bg-dark-800 rounded-xl">
                        <img src="{{ Storage::url($relatedRadio->img) }}" alt="{{ $relatedRadio->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" width="150" height="150">
                    </div>
                    <h3 class="text-center font-medium text-gray-300 group-hover:text-accent-red transition-colors text-sm truncate">{{ $relatedRadio->name }}</h3>
                </a>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Internal Links for SEO --}}
    <x-internal-links :exclude="$radio->genres->first()?->slug" />
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Favorites
    const radioId = '{{ $radio->id }}';
    const favButton = document.getElementById('fav-btn');
    function updateFavButton() {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const isFavorite = favorites.includes(radioId);
        favButton.innerHTML = isFavorite
            ? '<i class="fas fa-heart text-accent-red"></i> <span>En Favoritos</span>'
            : '<i class="fas fa-heart"></i> <span>Favorito</span>';
    }
    favButton.addEventListener('click', function() {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const idx = favorites.indexOf(radioId);
        if (idx > -1) { favorites.splice(idx, 1); } else { favorites.push(radioId); }
        localStorage.setItem('favorites', JSON.stringify(favorites));
        updateFavButton();
    });
    updateFavButton();

    // Real-time track
    const currentTrackUrl = '{{ route('radio.current-track', $radio->id) }}';
    function updateRealTimeData() {
        const controller = new AbortController();
        const t = setTimeout(() => controller.abort(), 5000);
        fetch(currentTrackUrl, { signal: controller.signal })
            .then(r => { clearTimeout(t); if (!r.ok) throw new Error(); return r.json(); })
            .then(data => {
                document.getElementById('current-track').textContent = data.current_track || 'No disponible';
                if (data.listeners) document.getElementById('listeners').innerHTML = '<i class="fas fa-headphones text-accent-red mr-2"></i> Oyentes: ' + data.listeners;
            })
            .catch(() => { clearTimeout(t); document.getElementById('current-track').textContent = 'Error de conexión'; });
    }
    updateRealTimeData();
    setInterval(updateRealTimeData, 30000);
});
</script>
@endsection
