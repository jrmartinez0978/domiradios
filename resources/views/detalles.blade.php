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
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $radio->name }} - Escucha en vivo {{ $radio->bitrate }}">
<meta name="twitter:description" content="Escucha {{ $radio->name }} en vivo. Emisora {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}.">
<meta name="twitter:image" content="{{ $radio->optimized_logo_url }}">

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
        'areaServed' => ['@type' => 'Country', 'name' => 'República Dominicana'],
        'broadcastTimezone' => 'America/Santo_Domingo',
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
        $cityForSchema = $radio->genres->where('type', 'city')->first()?->name ?? 'República Dominicana';
        $schemaData['contentLocation'] = ['@type' => 'Place', 'name' => $cityForSchema . ', República Dominicana'];
    }
    $schemaData['speakable'] = ['@type' => 'SpeakableSpecification', 'cssSelector' => ['h1', '.radio-description']];
@endphp
<script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endsection
@endif

@section('content')
@php
    $isRTC = $radio->source_radio === 'JRMStream';
    $isShoutcast = $radio->source_radio === 'Shoutcast';
    $sourceColor = $isRTC ? 'accent' : ($isShoutcast ? 'blue-500' : 'primary');
    $sourceLabel = $isRTC ? 'JRMStream' : ($isShoutcast ? 'Shoutcast' : 'Online');
    $genreNames = $radio->genres->where('type', 'genre')->pluck('name')->implode(', ');
    $cityName = $radio->genres->where('type', 'city')->first()?->name;
    $location = $radio->address ?: ($cityName ?: 'República Dominicana');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 pt-6">
    {{-- Breadcrumbs --}}
    <nav class="text-sm mb-6" aria-label="Breadcrumb">
        @php
        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => route('emisoras.index')],
            ['name' => 'Ciudades', 'url' => route('ciudades.index')],
        ];
        $breadcrumbCity = $radio->genres->where('type', 'city')->first();
        if ($breadcrumbCity) {
            $breadcrumbItems[] = ['name' => $breadcrumbCity->name, 'url' => route('ciudades.show', $breadcrumbCity->slug)];
        }
        $breadcrumbItems[] = ['name' => $radio->name];
        @endphp
        <x-breadcrumbs :items="$breadcrumbItems" />
    </nav>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 pb-8">
    {{-- TL;DR box for AI Overview --}}
    <div class="bg-white rounded-xl p-4 mb-6 border-l-4 border-{{ $sourceColor }} shadow-sm">
        <p class="text-gray-600 text-sm radio-description">
            <strong class="text-gray-800">{{ $radio->name }}</strong> es una emisora de radio dominicana que transmite en {{ $radio->bitrate }} desde {{ $cityName ?: 'República Dominicana' }}. {{ $genreNames ? "Géneros: {$genreNames}." : '' }} Escucha en vivo {{ Str::of($radio->tags)->explode(',')->first() }} y más. Transmisión online gratuita 24/7.
        </p>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        {{-- Hero Banner with Logo --}}
        <div class="relative bg-gradient-to-br from-primary via-primary to-primary/80 p-6 md:p-8">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('{{ $radio->optimized_logo_url }}'); background-size: cover; background-position: center; filter: blur(40px);"></div>
            </div>

            <div class="relative flex flex-col md:flex-row items-center gap-6">
                {{-- Logo --}}
                <div class="w-40 h-40 md:w-48 md:h-48 rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20 flex-shrink-0">
                    <img src="{{ $radio->optimized_logo_url }}" alt="Logo de {{ $radio->name }} - Emisora {{ $radio->bitrate }} en {{ $cityName ?: 'República Dominicana' }}" class="w-full h-full object-cover" width="300" height="300" fetchpriority="high">
                </div>

                {{-- Info --}}
                <div class="text-center md:text-left flex-1">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-3">
                        @if($radio->isFeatured)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-400 text-gray-900 text-xs font-black rounded-full uppercase tracking-wider">
                            <i class="fas fa-star text-[10px]"></i> Destacada
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 {{ $isRTC ? 'bg-accent' : ($isShoutcast ? 'bg-blue-500' : 'bg-white/20') }} text-white text-xs font-bold rounded-full">
                            @if($isRTC) <i class="fas fa-bolt text-[10px]"></i> JRMStream HD
                            @elseif($isShoutcast) <i class="fas fa-broadcast-tower text-[10px]"></i> Shoutcast
                            @else <i class="fas fa-globe text-[10px]"></i> Online
                            @endif
                        </span>
                        @if($radio->is_stream_active === false)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-500/80 text-white text-xs font-bold rounded-full">
                            <span class="relative flex h-1.5 w-1.5"><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white/50"></span></span>
                            Fuera de línea
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-500/80 text-white text-xs font-bold rounded-full">
                            <span class="relative flex h-1.5 w-1.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span></span>
                            En Vivo
                        </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">{{ $radio->name }}</h1>

                    @if($radio->bitrate)
                    <p class="text-white/80 text-lg font-semibold mb-2">
                        <i class="fas fa-broadcast-tower mr-1.5"></i>{{ $radio->bitrate }}
                    </p>
                    @endif

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-white/70 text-sm">
                        @if($genreNames)
                        <span><i class="fas fa-music mr-1.5"></i>{{ $genreNames }}</span>
                        @endif
                        <span><i class="fas fa-map-marker-alt mr-1.5"></i>{{ $location }}</span>
                    </div>

                    {{-- Rating --}}
                    <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                        <div class="user-rating cursor-pointer" data-radio-id="{{ $radio->id }}" data-current-rating="{{ $radio->rating }}">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star rating-star text-lg {{ $i <= $radio->rating ? 'text-amber-400' : 'text-white/30' }} hover:text-amber-400 transition-colors cursor-pointer" data-rating="{{ $i }}"></i>
                            @endfor
                        </div>
                        <span class="text-white/60 text-sm">{{ number_format($radio->rating, 1) }}/5</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Bar --}}
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
            if ($isRTC) {
                $pu = parse_url($radio->link_radio);
                $detailPlayData['rtcSlug'] = end(array_filter(explode('/', $pu['path'] ?? ''))) ?: $radio->slug;
                $detailPlayData['rtcServer'] = $pu['host'] ?? 'live.rtcstreaming.com';
                $detailPlayData['rtcPort'] = $pu['port'] ?? 9000;
            }
        @endphp
        <div class="bg-gray-50 border-b border-gray-100 px-6 md:px-8 py-4" x-data>
            <div class="flex flex-col sm:flex-row items-center gap-3">
                {{-- Play/Stop Button --}}
                <button
                    @click="
                        if ($store.player.radioId == {{ $radio->id }} && $store.player.isPlaying) {
                            $store.player.stop();
                        } else {
                            $store.player.play({{ Js::from($detailPlayData) }});
                        }
                    "
                    class="flex-1 sm:flex-none btn-primary !py-3.5 !px-8 text-base flex items-center justify-center gap-2 rounded-xl w-full sm:w-auto"
                >
                    <template x-if="$store.player.radioId == {{ $radio->id }} && $store.player.isPlaying">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-stop"></i> Detener
                        </span>
                    </template>
                    <template x-if="$store.player.radioId == {{ $radio->id }} && $store.player.isConnecting">
                        <span><i class="fas fa-circle-notch fa-spin mr-2"></i>Conectando...</span>
                    </template>
                    <template x-if="!($store.player.radioId == {{ $radio->id }} && ($store.player.isPlaying || $store.player.isConnecting))">
                        <span><i class="fas fa-play mr-2"></i>Escuchar en vivo</span>
                    </template>
                </button>

                @if($isRTC)
                <span class="text-xs text-accent font-semibold">
                    <i class="fas fa-bolt mr-0.5"></i> Baja latencia HD
                </span>
                @endif

                <div class="flex items-center gap-3 sm:ml-auto">
                    <button id="fav-btn" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-500 transition-all text-sm font-medium bg-white">
                        <i class="fas fa-heart"></i> <span>Favorito</span>
                    </button>

                    {{-- Social links --}}
                    @if($radio->url_website)
                    <a href="{{ $radio->url_website }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary/30 transition-all" title="Sitio web">
                        <i class="fas fa-globe"></i>
                    </a>
                    @endif
                    @if($radio->url_facebook)
                    <a href="{{ $radio->url_facebook }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-300 transition-all" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if($radio->url_twitter)
                    <a href="{{ $radio->url_twitter }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:text-sky-500 hover:border-sky-300 transition-all" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @endif
                    @if($radio->url_instagram)
                    <a href="{{ $radio->url_instagram }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:text-pink-500 hover:border-pink-300 transition-all" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Live Info Bar --}}
        <div class="px-6 md:px-8 py-4 border-b border-gray-100">
            @if($radio->is_stream_active === false)
            <div class="flex items-center gap-3 text-gray-500">
                <i class="fas fa-exclamation-triangle text-gray-400"></i>
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Estado del stream</h3>
                    <p class="text-sm font-medium text-gray-500">Esta emisora se encuentra fuera de línea temporalmente.</p>
                </div>
            </div>
            @else
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="flex-1">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                        <i class="fas fa-music mr-1.5 text-primary"></i>En directo ahora
                    </h3>
                    <div class="text-lg font-semibold text-gray-800" id="current-track">Cargando...</div>
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5" id="listeners">
                        <i class="fas fa-headphones text-primary"></i> Oyentes: 0
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fas fa-signal text-primary"></i> {{ $sourceLabel }}
                    </span>
                </div>
            </div>
            @endif
        </div>

        {{-- Details Grid --}}
        <div class="px-6 md:px-8 py-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <i class="fas fa-broadcast-tower text-primary text-lg mb-2"></i>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Frecuencia</p>
                    <p class="text-gray-800 font-bold text-lg">{{ $radio->bitrate }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <i class="fas fa-music text-primary text-lg mb-2"></i>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Género</p>
                    <p class="text-gray-800 font-semibold text-sm">{{ $genreNames ?: Str::limit($radio->tags, 30) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <i class="fas fa-map-marker-alt text-primary text-lg mb-2"></i>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Ciudad</p>
                    <p class="text-gray-800 font-semibold text-sm">{{ $cityName ?: 'R.D.' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <i class="fas fa-flag text-primary text-lg mb-2"></i>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">País</p>
                    <p class="text-gray-800 font-semibold text-sm">República Dominicana</p>
                </div>
            </div>

            @if(!empty($radio->address))
            <div class="bg-gray-50 rounded-xl p-4 mb-6 flex items-center gap-3">
                <i class="fas fa-road text-primary"></i>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Dirección</p>
                    <p class="text-gray-800 font-medium">{{ $radio->address }}</p>
                </div>
            </div>
            @endif

            {{-- Description --}}
            @if($radio->description)
            <div class="mt-2 pt-6 border-t border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle text-primary mr-2"></i>Acerca de {{ $radio->name }}
                </h2>
                <div class="prose max-w-none text-gray-600 text-sm leading-relaxed radio-description">{!! clean($radio->description) !!}</div>
            </div>
            @endif
        </div>

        {{-- Related radios --}}
        @if(isset($related) && count($related) > 0)
        <div class="px-6 md:px-8 py-6 border-t border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-broadcast-tower text-primary mr-2"></i>
                Emisoras similares
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @foreach($related as $relatedRadio)
                @if(is_object($relatedRadio) && isset($relatedRadio->slug))
                    <x-radio-card :radio="$relatedRadio" :loop="$loop" />
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Internal Links for SEO --}}
    <x-internal-links :exclude="$radio->genres->first()?->slug" />
</div>

<script>
document.addEventListener('livewire:navigated', function () {
    // Favorites
    const radioId = '{{ $radio->id }}';
    const favButton = document.getElementById('fav-btn');
    if (!favButton) return;
    function updateFavButton() {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const isFavorite = favorites.includes(radioId);
        favButton.innerHTML = isFavorite
            ? '<i class="fas fa-heart text-accent"></i> <span>En Favoritos</span>'
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

    // Real-time track (solo si el stream está activo)
    @if($radio->is_stream_active !== false)
    const currentTrackUrl = '{{ route('radio.current-track', $radio->id) }}';
    function updateRealTimeData() {
        const controller = new AbortController();
        const t = setTimeout(() => controller.abort(), 5000);
        fetch(currentTrackUrl, { signal: controller.signal })
            .then(r => { clearTimeout(t); if (!r.ok) throw new Error(); return r.json(); })
            .then(data => {
                const trackEl = document.getElementById('current-track');
                const listenersEl = document.getElementById('listeners');
                if (trackEl) trackEl.textContent = data.current_track || 'No disponible';
                if (data.listeners && listenersEl) listenersEl.innerHTML = '<i class="fas fa-headphones text-primary mr-2"></i> Oyentes: ' + data.listeners;
            })
            .catch(() => { clearTimeout(t); const el = document.getElementById('current-track'); if (el) el.textContent = 'Error de conexión'; });
    }
    updateRealTimeData();
    // Store interval reference for cleanup on navigation
    if (window._domiradiosTrackInterval) clearInterval(window._domiradiosTrackInterval);
    window._domiradiosTrackInterval = setInterval(updateRealTimeData, 30000);
    @endif
});
// Cleanup interval when navigating away (prevents memory leaks)
document.addEventListener('livewire:navigating', function () {
    if (window._domiradiosTrackInterval) {
        clearInterval(window._domiradiosTrackInterval);
        window._domiradiosTrackInterval = null;
    }
});
</script>
@endsection
