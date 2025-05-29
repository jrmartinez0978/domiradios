@php
    Log::info('[detalles.blade.php] Vista detalles renderizándose. isset($staticSeoHtml): ' . (isset($staticSeoHtml) ? 'Sí' : 'No'));
    if (isset($staticSeoHtml)) {
        Log::info('[detalles.blade.php] $staticSeoHtml no está vacío: ' . (!empty($staticSeoHtml) ? 'Sí' : 'No'));
        Log::info('[detalles.blade.php] $staticSeoHtml (primeros 100): ' . substr($staticSeoHtml, 0, 100));
    } else {
        Log::info('[detalles.blade.php] $staticSeoHtml no está definida.');
    }
@endphp
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
@if($radio->source_radio === 'RTCStream')
<!-- CSS del reproductor RTCStream -->
<link rel="stylesheet" href="/css/rtc-player.css">
@endif
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
    "ratingCount": "{{ rand(10, 50) }}"
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

                        {{-- SEO JSON-LD RadioStation --}}
                        @push('head')
                        <script type="application/ld+json">
                        {
                          "@context": "https://schema.org",
                          "@type": "RadioStation",
                          "name": "{{ $radio->name }}",
                          "url": "{{ url()->current() }}",
                          "logo": "{{ Storage::url($radio->img) }}",
                          "genre": "{{ $radio->tags }}",
                          "description": "{{ strip_tags($radio->description) }}",
                          "address": {
                            "@type": "PostalAddress"@if(!empty($radio->address)),
                            "streetAddress": "{{ $radio->address }}"@endif,
                            "addressLocality": "{{ $radio->genres->pluck('name')->implode(', ') }}",
                            "addressCountry": "República Dominicana"
                          }
                        }
                        </script>
                        @endpush
                        <p class="text-gray-700 flex items-center">
                            <span class="inline-block w-8 text-brand-blue"><i class="fas fa-flag"></i></span>
                            <span class="font-semibold mr-2">País:</span> República Dominicana
                        </p>
                    </div>
                </div>

                <!-- Redes sociales -->
                <div class="mt-4 flex flex-wrap gap-4">
                    @if($radio->url_website)
                    <a href="{{ $radio->url_website }}" target="_blank" class="text-gray-600 hover:text-brand-blue transition-colors flex items-center">
                        <i class="fas fa-globe mr-2"></i> Sitio web
                    </a>
                    @endif
                    @if($radio->url_facebook)
                    <a href="{{ $radio->url_facebook }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors flex items-center">
                        <i class="fab fa-facebook-f mr-2"></i> Facebook
                    </a>
                    @endif
                    @if($radio->url_twitter)
                    <a href="{{ $radio->url_twitter }}" target="_blank" class="text-blue-400 hover:text-blue-600 transition-colors flex items-center">
                        <i class="fab fa-twitter mr-2"></i> Twitter
                    </a>
                    @endif
                    @if($radio->url_instagram)
                    <a href="{{ $radio->url_instagram }}" target="_blank" class="text-pink-500 hover:text-pink-700 transition-colors flex items-center">
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

                <!-- Reproductor de audio -->
                <div class="mt-4">
                    @if($radio->source_radio === 'RTCStream')
                    <!-- RTCStream Player (WebRTC) siguiendo el modelo del ejemplo -->
                    <!-- Player sticky para mobile RTCStream -->
                    <!-- Mini-player sticky RTCStream para mobile -->
                    <div class="fixed bottom-0 left-0 w-full z-50 bg-white border-t border-gray-200 shadow-lg p-2 md:hidden flex flex-col items-center">
                        <div class="w-full max-w-md flex items-center justify-between">
                            <button id="btnPlay" class="flex-1 bg-gradient-to-r from-brand-blue to-brand-red text-white py-3 rounded-xl font-bold text-lg flex items-center justify-center mx-1">
                                <i class="fas fa-play mr-2 text-xl"></i> Escuchar
                            </button>
                            <button id="btnStop" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold text-lg flex items-center justify-center mx-1" disabled>
                                <i class="fas fa-stop mr-2 text-xl"></i> Detener
                            </button>
                        </div>
                        <div id="playerStatus" class="player-status status-info text-xs text-center p-2 mt-1 rounded-lg border border-gray-200 bg-white bg-opacity-80 w-full">Esperando conexión...</div>
                        <audio id="playerAudio" playsinline webkit-playsinline></audio>
                    </div>
                    <!-- Player RTCStream para escritorio -->
                    <div class="rtc-player relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 p-5 rounded-xl shadow-md border border-gray-200 md:block hidden" id="rtcPlayer">
                        <!-- Efectos visuales de fondo -->
                        <div class="absolute top-0 left-0 w-24 h-24 bg-emerald-500 rounded-full filter blur-3xl opacity-10 -translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 right-0 w-32 h-32 bg-brand-blue rounded-full filter blur-3xl opacity-10 translate-x-1/3 translate-y-1/3"></div>

                        <h3 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <span class="inline-block w-7 h-7 rounded-full bg-gradient-to-r from-emerald-500 to-green-600 mr-3 flex items-center justify-center shadow-sm">
                                <i class="fas fa-headphones-alt text-white text-xs"></i>
                            </span>
                            <span id="playerSlug" class="text-transparent bg-clip-text bg-gradient-to-r from-brand-blue to-brand-red">{{ Str::afterLast($radio->link_radio, '/') }}</span>
                        </h3>
                        <div class="text-xs mb-4 ml-10 font-bold italic drop-shadow-sm text-transparent bg-clip-text bg-gradient-to-r from-brand-blue to-brand-red">
                            <i class="fas fa-info-circle mr-1"></i> Baja latencia con RTCStream
                        </div>

                        <div class="flex gap-3 mb-4">
                            <button id="btnPlay" class="flex-1 bg-gradient-to-r from-brand-blue to-brand-red text-white py-3 rounded-lg font-medium flex items-center justify-center hover:shadow-lg hover:opacity-95 transform hover:-translate-y-0.5 transition-all duration-300 focus:ring focus:ring-brand-blue/30">
                                <i class="fas fa-play mr-2"></i> Escuchar
                            </button>
                            <button id="btnStop" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-medium flex items-center justify-center hover:bg-gray-300 hover:shadow-sm transform hover:-translate-y-0.5 transition-all duration-300" disabled>
                                <i class="fas fa-stop mr-2"></i> Detener
                            </button>
                        </div>

                        <div id="playerStatus" class="player-status status-info text-sm text-center p-2 rounded-lg border border-gray-200 bg-white bg-opacity-70 backdrop-blur-sm">Esperando conexión...</div>

                        <div class="flex justify-center items-center mt-3">
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 border border-gray-200">
                                <i class="fas fa-clock text-xs text-gray-500 mr-2"></i>
                                <div id="playerTimer" class="text-xs font-mono text-gray-700">00:00</div>
                            </div>
                        </div>

                        <audio id="playerAudio" playsinline webkit-playsinline></audio>
                    </div>

                    @else
                    <!-- Reproductor Estándar HTML5 -->
                    @if($radio->link_radio)
                    <div class="fixed bottom-0 left-0 w-full z-50 bg-white border-t border-gray-200 shadow-lg p-2 md:hidden flex flex-col items-center">
                        <audio id="audio-player" src="{{ $radio->link_radio }}"></audio>
                        <button id="play-btn" class="w-full max-w-md bg-gradient-to-r from-brand-blue to-brand-red text-white py-4 rounded-xl font-bold text-lg flex items-center justify-center hover:opacity-90 focus:ring focus:ring-brand-blue/30 transition-all">
                            <i class="fas fa-play mr-3 text-xl"></i> Escuchar en Vivo
                        </button>
                    </div>
                    <div class="md:block hidden">
                        <audio id="audio-player-desktop" src="{{ $radio->link_radio }}"></audio>
                        <button id="play-btn-desktop" class="w-full bg-gradient-to-r from-brand-blue to-brand-red text-white py-3 rounded-lg font-medium flex items-center justify-center hover:opacity-90 transition-all focus:ring focus:ring-brand-blue/30">
                            <i class="fas fa-play mr-2"></i> Reproducir
                        </button>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Descripción -->
        @if($radio->description)
        <div class="mt-6 p-6 border-t border-gray-100 bg-white rounded-xl shadow-md">
            <h2 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                <span class="text-brand-blue mr-2"><i class="fas fa-info-circle"></i></span>
                Acerca de esta emisora
            </h2>
            <div class="prose max-w-none text-gray-700">{!! $radio->description !!}</div>
        </div>
        @endif
    </div>

    <!-- Emisoras relacionadas -->
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
            <span class="text-brand-blue mr-2"><i class="fas fa-broadcast-tower"></i></span>
            Otras emisoras de {{ $radio->genres->pluck('name')->implode(', ') }}
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $related)
                <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 group">
                    <a href="{{ route('emisoras.show', ['slug' => $related->slug]) }}" class="block">
                        <div class="h-32 flex items-center justify-center mb-3 overflow-hidden bg-gray-50 rounded-lg p-2">
                            <img src="{{ Storage::url($related->img) }}" alt="{{ $related->name }}" class="mx-auto max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <h3 class="text-center font-medium text-gray-800 group-hover:text-brand-red transition-colors">{{ $related->name }}</h3>
                    </a>
                </div>
            @endforeach
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

<script>
    // Reproductor de audio con intentos de conexión y registro de visitas
    document.addEventListener('DOMContentLoaded', function () {
        const audioPlayer = document.getElementById('audio-player');
        const playButton = document.getElementById('play-btn');
        const radioId = '{{ $radio->id }}';  // ID de la emisora actual
        const maxAttempts = 3;  // Número máximo de intentos de conexión
        let connectionAttempts = 0;
        let isTryingToPlay = false;
        let hasRegisteredPlay = false;
        const csrfToken = '{{ csrf_token() }}';

        // Función para reproducir o pausar el audio
        function toggleAudio() {
            if (audioPlayer.paused) {
                playButton.disabled = true;
                playButton.textContent = 'Conectando...';
                connectionAttempts = 0;
                isTryingToPlay = true;
                tryPlayAudio();

                // Registrar la visita si aún no se ha hecho
                if (!hasRegisteredPlay) {
                    registerPlay();
                    hasRegisteredPlay = true;
                }
            } else {
                audioPlayer.pause();
                playButton.textContent = 'Reproducir';
            }
        }

        function tryPlayAudio() {
            if (connectionAttempts < maxAttempts) {
                connectionAttempts++;
                audioPlayer.load(); // Reiniciar el reproductor
                audioPlayer.play().then(() => {
                    playButton.textContent = 'Pausar';
                    playButton.disabled = false;
                    isTryingToPlay = false;
                    connectionAttempts = 0; // Reiniciar los intentos si se conecta
                }).catch((error) => {
                    console.error('Error al reproducir el audio:', error);
                    setTimeout(tryPlayAudio, 2000); // Esperar 2 segundos antes del próximo intento
                });
            } else {
                playButton.textContent = 'Fuera de línea';
                playButton.disabled = true;
                isTryingToPlay = false;
                audioPlayer.pause();
            }
        }

        // Función para registrar la visita al hacer clic en reproducir
        function registerPlay() {
            fetch('{{ route('radio.register-play') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ radio_id: radioId }),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Visita registrada:', data);
            })
            .catch(error => {
                console.error('Error al registrar la visita:', error);
            });
        }

        // Evento para reproducir o pausar el audio al hacer clic en el botón
        playButton.addEventListener('click', toggleAudio);

        // Evento para manejar errores durante la reproducción
        audioPlayer.addEventListener('error', function () {
            console.error('Error en el reproductor de audio.');
            audioPlayer.pause();
            if (connectionAttempts >= maxAttempts) {
                playButton.textContent = 'Fuera de línea';
                playButton.disabled = true;
                isTryingToPlay = false;
            } else if (isTryingToPlay) {
                setTimeout(tryPlayAudio, 2000); // Intentar nuevamente
            }
        });

        // Evento para manejar la finalización de la reproducción
        audioPlayer.addEventListener('ended', function () {
            playButton.textContent = 'Reproducir';
        });

        // Configurar MediaSession API para mostrar información en la pantalla de bloqueo
        if ('mediaSession' in navigator) {
            audioPlayer.addEventListener('play', updateMediaSession);

            // Actualizar la información de MediaSession
            function updateMediaSession() {
                let track = document.getElementById('current-track').textContent;
                if (track === 'Cargando canción...') {
                    track = '{{ $radio->name }} - En vivo';
                }

                navigator.mediaSession.metadata = new MediaMetadata({
                    title: track,
                    artist: '{{ $radio->name }}',
                    album: '{{ Str::of($radio->tags)->explode(',')->first() }}',
                    artwork: [
                        { src: '{{ url(Storage::url($radio->img)) }}', sizes: '96x96', type: 'image/png' },
                        { src: '{{ url(Storage::url($radio->img)) }}', sizes: '128x128', type: 'image/png' },
                        { src: '{{ url(Storage::url($radio->img)) }}', sizes: '192x192', type: 'image/png' },
                        { src: '{{ url(Storage::url($radio->img)) }}', sizes: '256x256', type: 'image/png' },
                        { src: '{{ url(Storage::url($radio->img)) }}', sizes: '384x384', type: 'image/png' },
                        { src: '{{ url(Storage::url($radio->img)) }}', sizes: '512x512', type: 'image/png' }
                    ]
                });

                // Configurar los controladores de acciones para MediaSession
                navigator.mediaSession.setActionHandler('play', () => {
                    audioPlayer.play();
                    playButton.textContent = 'Pausar';
                });

                navigator.mediaSession.setActionHandler('pause', () => {
                    audioPlayer.pause();
                    playButton.textContent = 'Reproducir';
                });
            }

            // Actualizar la metadata cada vez que cambie la canción actual
            const trackObserver = new MutationObserver(() => {
                if (audioPlayer.paused === false) {
                    updateMediaSession();
                }
            });

            trackObserver.observe(document.getElementById('current-track'), {
                childList: true,
                characterData: true,
                subtree: true
            });
        }
    });
</script>

@if($radio->source_radio === 'RTCStream')
<!-- Script RTCStream Player - Versión optimizada -->
<script src="https://live.rtcstreaming.com:9000/mediasoup-client.min.js"></script>
<script src="/js/rtc-player-v2.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  try {
    // URL directa desde la base de datos
    const rtcUrl = "{{ $radio->link_radio }}";

    // Comprobar si la URL es válida
    if (!rtcUrl) {
      console.error('Error: No hay URL de WebSocket configurada');
      if (document.getElementById('rtcStatus')) {
        document.getElementById('rtcStatus').textContent = 'Error: URL no configurada';
        document.getElementById('rtcStatus').className = 'status error';
      }
      return;
    }

    // Extraer slug para la interfaz
    const slug = rtcUrl.split('/').pop() || '{{ $radio->slug }}';

    // Configurar metatags para dispositivos móviles
    ['apple-mobile-web-app-capable', 'apple-mobile-web-app-status-bar-style', 'apple-mobile-web-app-title'].forEach((name, i) => {
      const content = i === 0 ? 'yes' : i === 1 ? 'black-translucent' : '{{ $radio->name }}';
      if (!document.querySelector(`meta[name="${name}"]`)) {
        const meta = document.createElement('meta');
        meta.name = name;
        meta.content = content;
        document.head.appendChild(meta);
      }
    });

    // Esperar a que los scripts se carguen completamente
    const checkScriptsLoaded = setInterval(function() {
      if (window.RTCStreamPlayer && window.mediasoupClient) {
        clearInterval(checkScriptsLoaded);

        // Inicializar el reproductor
        window.RTCStreamPlayer.init({
          wsUrl: rtcUrl,
          title: "{{ $radio->name }} - En vivo",
          artist: "{{ $radio->name }}",
          autoplay: false,
          artwork: [
            { src: "{{ url(Storage::url($radio->img)) }}", sizes: '96x96', type: 'image/png' },
            { src: "{{ url(Storage::url($radio->img)) }}", sizes: '256x256', type: 'image/png' }
          ]
        });

        // Actualizar interfaz
        const slugDisplay = document.getElementById('playerSlug');
        if (slugDisplay) slugDisplay.textContent = slug;
      }
    }, 100);

    // Timeout de seguridad después de 5 segundos
    setTimeout(function() {
      if (!window.RTCStreamPlayer || !window.mediasoupClient) {
        clearInterval(checkScriptsLoaded);
        console.error('Error: No se pudieron cargar los scripts necesarios después de 5 segundos');
        if (document.getElementById('rtcStatus')) {
          document.getElementById('rtcStatus').textContent = 'Error: No se pudieron cargar los scripts';
          document.getElementById('rtcStatus').className = 'status error';
        }
        // Marcar la página como cargada para el navegador (en caso de bloqueos)
        if (window.stop) window.stop();
        if (document.readyState !== 'complete') {
          window.addEventListener('load', function() {}, { once: true });
        }
      }
    }, 5000);

  } catch (error) {
    console.error('Error al inicializar RTCStreamPlayer:', error);
    if (document.getElementById('rtcStatus')) {
      document.getElementById('rtcStatus').textContent = 'Error: ' + error.message;
      document.getElementById('rtcStatus').className = 'status error';
    }
  }
});
</script>
@endif

<script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "RadioStation",
          "name": "{{ $radio->name }}",
          "url": "{{ url()->current() }}",
          "logo": "{{ Storage::url($radio->img) }}",
          "sameAs": [
            "{{ $radio->url_website }}",
            "{{ $radio->url_facebook }}",
            "{{ $radio->url_twitter }}",
            "{{ $radio->url_instagram }}"
          ],
          "areaServed": "{{ $radio->genres->pluck('name')->implode(', ') }}",
          "description": "{{ strip_tags($radio->description) }}",
          "genre": "{{ $radio->tags }}",
          "location": {
              "@type": "Place",
              "address": {
                  "@type": "PostalAddress",
                  "addressLocality": "{{ $radio->genres->pluck('name')->implode(', ') }}",
                  "addressCountry": "DO"
              }
          }
        }
    </script>


@endsection
