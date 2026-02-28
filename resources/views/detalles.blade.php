@extends('layouts.app')

@if(isset($staticSeoHtml) && $staticSeoHtml)
    @section('static_seo_tags')
        {!! $staticSeoHtml !!}
    @endsection
@else

@section('title', $radio->name.' - Escucha en vivo '.$radio->bitrate.' - Domiradios')

@section('meta_description', 'Escucha ' . $radio->name . ' en vivo por internet. Emisora de radio ' . $radio->bitrate . ' - ' . Str::of($radio->tags)->explode(',')->first() . '. Transmisión online desde República Dominicana.')

@section('meta_keywords', $radio->name . ', ' . $radio->bitrate . ', radio online, emisora dominicana, ' . $radio->tags . ', radio en vivo, escuchar radio')

@section('head_additional')
@if(false){{-- RTCStream ya no necesita CSS separado --}}@endif
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $radio->name }} - Escucha en vivo {{ $radio->bitrate }}">
<meta property="og:description" content="Escucha {{ $radio->name }} en vivo. Emisora de radio {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}. Transmisión online desde República Dominicana.">
<meta property="og:image" content="{{ $radio->optimized_logo_url }}">
<meta property="og:url" content="{{ url()->current() }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="{{ $radio->name }} - Escucha en vivo {{ $radio->bitrate }}">
<meta property="twitter:description" content="Escucha {{ $radio->name }} en vivo. Emisora de radio {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}. Transmisión online desde República Dominicana.">
<meta property="twitter:image" content="{{ $radio->optimized_logo_url }}">

<!-- JSON-LD para SEO avanzado -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "RadioStation",
  "name": "{{ $radio->name }}",
  "url": "{{ url()->current() }}",
  "logo": "{{ $radio->optimized_logo_url }}",
  "image": "{{ $radio->optimized_logo_url }}",
  "description": "Escucha {{ $radio->name }} en vivo. Emisora de radio {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}.",
  @if(!empty($radio->address))
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ $radio->address }}",
    "addressCountry": "República Dominicana"
  },
  @endif
  @if(!empty($radio->telephone))
  "telephone": "{{ $radio->telephone }}",
  @endif
  @if(!empty($radio->email))
  "email": "{{ $radio->email }}",
  @endif
  @if(!empty($radio->opening_hours))
  "openingHours": "{{ $radio->opening_hours }}",
  @endif
  @php
    $sameAs = [];
    if (!empty($radio->url_website)) $sameAs[] = $radio->url_website;
    if (!empty($radio->url_facebook)) $sameAs[] = $radio->url_facebook;
    if (!empty($radio->url_twitter)) $sameAs[] = $radio->url_twitter;
    if (!empty($radio->url_instagram)) $sameAs[] = $radio->url_instagram;
  @endphp
  @if(count($sameAs))
  "sameAs": [{!! collect($sameAs)->map(fn($url) => '"'.$url.'"')->implode(',') !!}],
  @endif
  @if(!empty($radio->latitude) && !empty($radio->longitude))
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "{{ $radio->latitude }}",
    "longitude": "{{ $radio->longitude }}"
  },
  @endif
  @if(empty($radio->address))
  "contentLocation": {
    "@type": "Place",
    "name": "{{ $radio->genres->pluck('name')->implode(', ') }}, República Dominicana"
  },
  @endif
  "genre": "{{ $radio->tags }}",
  "frequency": "{{ $radio->bitrate }}",
  @if(!empty($radio->rating))
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $radio->rating }}",
    "bestRating": "5",
    "worstRating": "1",
    "ratingCount": "1"
  },
  @endif
  @if(!empty($radio->link_radio))
  "audio": {
    "@type": "AudioObject",
    "contentUrl": "{{ $radio->link_radio }}",
    "encodingFormat": "{{ $radio->type_radio }}"
  }
  @endif
}
</script>
@endsection
@endif

@section('content')
<div class="container max-w-7xl mx-auto px-4 pt-8">
    <nav class="text-sm mb-6 text-gray-600" aria-label="Breadcrumb">
        @php
        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => route('emisoras.index')],
            ['name' => 'Ciudades', 'url' => route('ciudades.index')],
        ];
        if ($radio->genres->count() > 0) {
            $firstGenre = $radio->genres->first();
            if ($firstGenre) { // Asegurarse que $firstGenre no es null
                $breadcrumbItems[] = ['name' => $firstGenre->name, 'url' => route('ciudades.show', $firstGenre->slug)];
            }
        }
        $breadcrumbItems[] = ['name' => $radio->name];
        @endphp
        <x-breadcrumbs :items="$breadcrumbItems" />
    </nav>
</div>
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="md:flex">
            <div class="md:w-1/3 p-6 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                <img src="{{ $radio->optimized_logo_url }}" alt="{{ $radio->name }}" class="max-w-full h-auto max-h-56 rounded-lg shadow-sm">
            </div>
            <div class="md:w-2/3 p-6 md:p-8">
                <h1 class="text-3xl font-bold mb-4 text-gray-800">{{ $radio->name }}</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="space-y-2">
                        <p class="text-gray-700 flex items-center">
                            <span class="inline-block w-8 text-brand-blue"><i class="fas fa-broadcast-tower"></i></span>
                            <span class="font-semibold mr-2">Frecuencia:</span> {{ $radio->bitrate }}
                        </p>
                        <p class="text-gray-700 flex items-center">
                            <span class="inline-block w-8 text-brand-blue"><i class="fas fa-music"></i></span>
                            <span class="font-semibold mr-2">Género:</span> {{ $radio->tags }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-700 flex items-center">
                            <span class="inline-block w-8 text-brand-blue"><i class="fas fa-map-marker-alt"></i></span>
                            <span class="font-semibold mr-2">Ciudad:</span> {{ $radio->genres->pluck('name')->implode(', ') }}
                        </p>
                        <p class="text-gray-700 flex items-center">
                            <span class="inline-block w-8 text-brand-blue"><i class="fas fa-road"></i></span>
                            <span class="font-semibold mr-2">Dirección:</span>
                            @if(!empty($radio->address))
                                {{ $radio->address }}
                            @else
                                No disponible
                            @endif
                        </p>

                        <p class="text-gray-700 flex items-center">
                            <span class="inline-block w-8 text-brand-blue"><i class="fas fa-flag"></i></span>
                            <span class="font-semibold mr-2">País:</span> República Dominicana
                        </p>
                    </div>
                </div>

                <!-- Redes sociales -->
                <div class="mt-4 flex flex-wrap gap-4">
                    @if($radio->url_website)
                    <a href="{{ $radio->url_website }}" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-brand-blue transition-colors flex items-center">
                        <i class="fas fa-globe mr-2"></i> Sitio web
                    </a>
                    @endif
                    @if($radio->url_facebook)
                    <a href="{{ $radio->url_facebook }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 transition-colors flex items-center">
                        <i class="fab fa-facebook-f mr-2"></i> Facebook
                    </a>
                    @endif
                    @if($radio->url_twitter)
                    <a href="{{ $radio->url_twitter }}" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-600 transition-colors flex items-center">
                        <i class="fab fa-twitter mr-2"></i> Twitter
                    </a>
                    @endif
                    @if($radio->url_instagram)
                    <a href="{{ $radio->url_instagram }}" target="_blank" rel="noopener noreferrer" class="text-pink-500 hover:text-pink-700 transition-colors flex items-center">
                        <i class="fab fa-instagram mr-2"></i> Instagram
                    </a>
                    @endif
                </div>



                <!-- Guardar en Favoritos y Clasificación -->
                <!-- Información en tiempo real -->
                <div class="mt-6 bg-gradient-to-r from-brand-blue/5 to-brand-red/5 p-5 rounded-lg">
                    <div class="mb-3">
                        <h3 class="font-semibold text-gray-800 flex items-center">
                            <span class="text-brand-blue mr-2"><i class="fas fa-music"></i></span>
                            En directo ahora:
                        </h3>
                        <div class="text-lg font-medium mt-1" id="current-track">Cargando canción...</div>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center" id="listeners">
                                <i class="fas fa-headphones text-brand-blue mr-2"></i> Oyentes: 0
                            </div>
                            <div class="bg-gray-100 p-3 rounded-lg mt-2 mb-2 border border-gray-200">
                                <h3 class="text-gray-800 font-semibold mb-2 flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-2"></i> ¡Valora esta emisora!
                                </h3>
                                <div class="flex items-center justify-between">
                                    <div class="user-rating cursor-pointer" data-radio-id="{{ $radio->id }}" data-current-rating="{{ $radio->rating }}">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star rating-star text-2xl {{ $i <= $radio->rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-500 transition-colors" data-rating="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-500 italic">Haz clic para valorar</span>
                                </div>
                                <div class="text-sm text-gray-600 mt-1">
                                    Rating actual: <span class="font-semibold">{{ number_format($radio->rating, 1) }}/5</span>
                                </div>
                            </div>
                        </div>

                        <button id="fav-btn" class="bg-brand-blue text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-colors flex items-center">
                            <i class="fas fa-heart mr-2"></i> Agregar a Favoritos
                        </button>
                    </div>
                </div>

                <!-- Reproductor flotante siempre visible, solo el player, esquina inferior derecha -->
                <div class="fixed bottom-0 left-0 right-0 w-full md:bottom-8 md:right-8 md:left-auto md:w-[400px] z-50 bg-white rounded-t-2xl md:rounded-2xl shadow-2xl p-4 flex flex-col items-center gap-3">
                    <audio id="playerAudio" playsinline webkit-playsinline preload="none" class="w-full rounded-lg mb-2"
                        @if($radio->source_radio !== 'RTCStream') src="{{ $radio->link_radio }}" @endif
                    ></audio>
                    <div class="flex gap-3 w-full">
                        <button id="btnPlay" class="flex-1 bg-gradient-to-r from-brand-blue to-brand-red text-white py-3 rounded-lg font-medium flex items-center justify-center hover:shadow-lg hover:opacity-95 transition-all">
                            <i class="fas fa-play mr-2"></i> Escuchar
                        </button>
                        <button id="btnStop" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-medium flex items-center justify-center hover:bg-gray-300 transition-all" disabled>
                            <i class="fas fa-stop mr-2"></i> Detener
                        </button>
                    </div>
                </div>

                <!-- Descripción -->
                @if($radio->description)
                <div class="mt-6 p-6 border-t border-gray-100 bg-white rounded-xl shadow-md">
                    <h2 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                        <span class="text-brand-blue mr-2"><i class="fas fa-info-circle"></i></span>
                        Acerca de esta emisora
                    </h2>
                    <div class="prose max-w-none text-gray-700">{!! strip_tags($radio->description, '<p><br><b><strong><i><em><ul><ol><li><a><h2><h3><h4>') !!}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Emisoras relacionadas -->
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
                <span class="text-brand-blue mr-2"><i class="fas fa-broadcast-tower"></i></span>
                Otras emisoras de {{ $radio->genres->pluck('name')->implode(', ') }}
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($related as $relatedRadio)
    @if(is_object($relatedRadio) && isset($relatedRadio->slug))
        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 group">
            <a href="{{ route('emisoras.show', ['slug' => $relatedRadio->slug]) }}" class="block">
                <div class="h-32 flex items-center justify-center mb-3 overflow-hidden bg-gray-50 rounded-lg p-2">
                    <img src="{{ Storage::url($relatedRadio->img) }}" alt="{{ $relatedRadio->name }}" class="mx-auto max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                </div>
                <h3 class="text-center font-medium text-gray-800 group-hover:text-brand-red transition-colors">{{ $relatedRadio->name }}</h3>
            </a>
        </div>
    @endif
@endforeach
            </div>
        </div>
    </div>

</div>

<script>
// Agregar a favoritos
document.addEventListener('DOMContentLoaded', function () {
    const radioId = '{{ $radio->id }}';  // ID de la emisora actual
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const favButton = document.getElementById('fav-btn');

    // Función para actualizar el estado del botón de favoritos
    function updateFavButton() {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const isFavorite = favorites.includes(radioId);

        if (isFavorite) {
            favButton.textContent = 'En Favoritos';
            favButton.classList.remove('bg-green-600', 'hover:bg-green-700');
            favButton.classList.add('bg-red-600', 'hover:bg-red-700');
        } else {
            favButton.textContent = 'Agregar a Favoritos';
            favButton.classList.remove('bg-red-600');
            favButton.classList.add('bg-green-600');
            favButton.onclick = toggleFavorite;
        }
    }

    // Función para agregar o quitar de favoritos
    function toggleFavorite() {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const isFavorite = favorites.includes(radioId);

        if (isFavorite) {
            // Eliminar de favoritos
            favorites = favorites.filter(id => id !== radioId);
            favButton.textContent = 'Agregar a Favoritos';
            favButton.classList.remove('bg-red-600');
            favButton.classList.add('bg-green-600');
        } else {
            // Agregar a favoritos
            favorites.push(radioId);
            favButton.textContent = 'En Favoritos';
            favButton.classList.remove('bg-green-600');
            favButton.classList.add('bg-red-600');
        }

        localStorage.setItem('favorites', JSON.stringify(favorites));
        updateFavButton();  // Actualizar el botón después de la acción
    }

    // Inicializar el estado del botón al cargar la página
    updateFavButton();
});
</script>

<script>
// Actualizar la canción y oyentes en tiempo real
document.addEventListener('DOMContentLoaded', function () {
    const radioId = '{{ $radio->id }}';  // ID de la emisora actual
    const currentTrackUrl = '{{ route('radio.current-track', $radio->id) }}';  // Genera la URL correcta

    // Función para actualizar la canción y oyentes en tiempo real
    function updateRealTimeData() {
        // Fetch con timeout y control de error robusto
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 segundos de timeout
        fetch(currentTrackUrl, { signal: controller.signal })
            .then(response => {
                clearTimeout(timeoutId);
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.current_track) {
                    document.getElementById('current-track').textContent = data.current_track;
                } else {
                    document.getElementById('current-track').textContent = 'Información no disponible';
                }
                if (data.listeners) {
                    document.getElementById('listeners').textContent = 'Oyentes: ' + data.listeners;
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                document.getElementById('current-track').textContent = 'Error de conexión';
                document.getElementById('listeners').textContent = 'Oyentes: --';
                console.error('Error al obtener los datos:', error);
            });
    }
    updateRealTimeData();  // Inicializar la actualización en tiempo real
    setInterval(updateRealTimeData, 30000);  // Actualizar cada 30 segundos
});
</script>

<!-- Script universal para el reproductor flotante -->
@if($radio->source_radio === 'RTCStream')
@php
    $parsedUrl = parse_url($radio->link_radio);
    $rtcServerUrl = $parsedUrl['host'] ?? 'live.rtcstreaming.com';
    $rtcPort = $parsedUrl['port'] ?? 9000;
    $rtcPath = $parsedUrl['path'] ?? '';
    $pathParts = array_filter(explode('/', $rtcPath));
    $rtcSlug = end($pathParts) ?: $radio->slug;
@endphp
<!-- Pre-cargar scripts RTCStream (necesario para mantener gesto del usuario) -->
<script src="/js/rtc-player-v2.js?v=2.0.1"></script>
<script src="https://{{ $rtcServerUrl }}:{{ $rtcPort }}/mediasoup-client.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var playBtn = document.getElementById('btnPlay');
  var stopBtn = document.getElementById('btnStop');
  var audio = document.getElementById('playerAudio');
  var player = null;
  var isConnected = false;

  function setPlay() {
    playBtn.disabled = false;
    playBtn.innerHTML = '<i class="fas fa-play mr-2"></i> Escuchar';
    stopBtn.disabled = true;
  }
  function setConnecting() {
    playBtn.disabled = true;
    playBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Conectando...';
    stopBtn.disabled = false;
  }
  function setPlaying() {
    playBtn.disabled = true;
    playBtn.innerHTML = '<i class="fas fa-volume-up mr-2"></i> En vivo';
    stopBtn.disabled = false;
  }

  function setupMediaSession() {
    if (!('mediaSession' in navigator)) return;
    navigator.mediaSession.metadata = new MediaMetadata({
      title: "{{ addslashes($radio->name) }} - En vivo",
      artist: "{{ addslashes($radio->name) }}",
      album: 'Radio en vivo',
      artwork: [
        { src: "{{ url(Storage::url($radio->img)) }}", sizes: '96x96', type: 'image/png' },
        { src: "{{ url(Storage::url($radio->img)) }}", sizes: '256x256', type: 'image/png' }
      ]
    });
    navigator.mediaSession.setActionHandler('play', function() { if (!isConnected) doConnect(); });
    navigator.mediaSession.setActionHandler('pause', function() { doStop(); });
  }

  function doConnect() {
    setConnecting();

    // Verificar que las librerías estén cargadas
    if (typeof RTCStreamPlayer === 'undefined' || typeof mediasoupClient === 'undefined') {
      console.error('RTCStream: Librerías no cargadas');
      setPlay();
      return;
    }

    // Crear player (sincrónico - mantiene contexto del gesto del usuario)
    player = RTCStreamPlayer.create({
      slug: "{{ $rtcSlug }}",
      serverUrl: "{{ $rtcServerUrl }}",
      port: {{ $rtcPort }}
    });

    player.on('stream', function(mediaStream) {
      console.log('RTCStream: Stream recibido');
      audio.srcObject = mediaStream;
      player.attachAudio(audio);
      var ctx = player.getAudioContext();
      if (ctx && ctx.state !== 'running') ctx.resume();
      audio.play().then(function() {
        console.log('RTCStream: Reproduciendo');
      }).catch(function(e) {
        console.error('RTCStream: Error en play():', e);
        setPlay();
      });
    });

    player.on('state', function(info) {
      console.log('RTCStream: Estado:', info.state, info.detail || '');
      if (info.state === 'connected') {
        isConnected = true;
      }
      if (info.state === 'connecting') {
        setConnecting();
      }
      if (info.state === 'disconnected') {
        isConnected = false;
        setPlay();
      }
    });

    // connect() crea AudioContext sincronicamente (dentro del gesto del click)
    player.connect();
  }

  function doStop() {
    isConnected = false;
    if (player) { player.disconnect(); player = null; }
    audio.pause();
    audio.srcObject = null;
    setPlay();
  }

  // Eventos del audio element
  audio.addEventListener('playing', function() { setPlaying(); setupMediaSession(); });
  audio.addEventListener('pause', function() { if (!isConnected) setPlay(); });
  audio.addEventListener('error', function(e) { console.error('RTCStream: Audio error', e); setPlay(); });

  playBtn.addEventListener('click', doConnect);
  stopBtn.addEventListener('click', doStop);
});
</script>
@else
<script>
document.addEventListener('DOMContentLoaded', function() {
  var playBtn = document.getElementById('btnPlay');
  var stopBtn = document.getElementById('btnStop');
  var audio = document.getElementById('playerAudio');

  playBtn.addEventListener('click', function() {
    playBtn.disabled = true;
    playBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Conectando...';
    audio.play();
  });

  stopBtn.addEventListener('click', function() {
    audio.pause();
    stopBtn.disabled = true;
    playBtn.disabled = false;
    playBtn.innerHTML = '<i class="fas fa-play mr-2"></i> Escuchar';
  });

  audio.addEventListener('playing', function() {
    playBtn.disabled = true;
    playBtn.innerHTML = '<i class="fas fa-volume-up mr-2"></i> En vivo';
    stopBtn.disabled = false;
    // MediaSession
    if ('mediaSession' in navigator) {
      navigator.mediaSession.metadata = new MediaMetadata({
        title: "{{ addslashes($radio->name) }} - En vivo",
        artist: "{{ addslashes($radio->name) }}",
        album: 'Radio en vivo',
        artwork: [
          { src: "{{ url(Storage::url($radio->img)) }}", sizes: '96x96', type: 'image/png' },
          { src: "{{ url(Storage::url($radio->img)) }}", sizes: '256x256', type: 'image/png' }
        ]
      });
      navigator.mediaSession.setActionHandler('play', function() { audio.play(); });
      navigator.mediaSession.setActionHandler('pause', function() { audio.pause(); });
    }
  });

  audio.addEventListener('pause', function() {
    stopBtn.disabled = true;
    playBtn.disabled = false;
    playBtn.innerHTML = '<i class="fas fa-play mr-2"></i> Escuchar';
  });

  audio.addEventListener('error', function() {
    stopBtn.disabled = true;
    playBtn.disabled = false;
    playBtn.innerHTML = '<i class="fas fa-play mr-2"></i> Escuchar';
  });
});
</script>
@endif




@endsection

