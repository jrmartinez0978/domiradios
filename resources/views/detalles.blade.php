@extends('layouts.app')

@section('title', $radio->name.' - Escucha en vivo '.$radio->bitrate.' - Domiradios')

@section('meta_description', 'Escucha ' . $radio->name . ' en vivo por internet. Emisora de radio ' . $radio->bitrate . ' - ' . Str::of($radio->tags)->explode(',')->first() . '. Transmisión online desde República Dominicana.')

@section('meta_keywords', $radio->name . ', ' . $radio->bitrate . ', radio online, emisora dominicana, ' . $radio->tags . ', radio en vivo, escuchar radio')

@section('head_additional')
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $radio->name }} - Escucha en vivo {{ $radio->bitrate }}">
<meta property="og:description" content="Escucha {{ $radio->name }} en vivo. Emisora de radio {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}. Transmisión online desde República Dominicana.">
<meta property="og:image" content="{{ url(Storage::url($radio->img)) }}">
<meta property="og:url" content="{{ url()->current() }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="{{ $radio->name }} - Escucha en vivo {{ $radio->bitrate }}">
<meta property="twitter:description" content="Escucha {{ $radio->name }} en vivo. Emisora de radio {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}. Transmisión online desde República Dominicana.">
<meta property="twitter:image" content="{{ url(Storage::url($radio->img)) }}">

<!-- JSON-LD para SEO avanzado -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "RadioStation",
  "name": "{{ $radio->name }}",
  "url": "{{ url()->current() }}",
  "logo": "{{ url(Storage::url($radio->img)) }}",
  "image": "{{ url(Storage::url($radio->img)) }}",
  "description": "Escucha {{ $radio->name }} en vivo. Emisora de radio {{ $radio->bitrate }} - {{ Str::of($radio->tags)->explode(',')->first() }}.",
  "contentLocation": {
    "@type": "Place",
    "name": "{{ $radio->genres->pluck('name')->implode(', ') }}, República Dominicana"
  },
  "genre": "{{ $radio->tags }}",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $radio->rating }}",
    "bestRating": "5",
    "worstRating": "1",
    "ratingCount": "{{ rand(10, 50) }}"
  },
  "audio": {
    "@type": "AudioObject",
    "contentUrl": "{{ $radio->link_radio }}",
    "encodingFormat": "{{ $radio->type_radio }}"
  }
}
</script>
@endsection

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="md:flex">
            <div class="md:w-1/3 p-6 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                <img src="{{ Storage::url($radio->img) }}" alt="{{ $radio->name }}" class="max-w-full h-auto max-h-56 rounded-lg shadow-sm">
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
                    <audio id="audio-player" src="{{ $radio->link_radio }}"></audio>
                    <button id="play-btn" class="w-full bg-gradient-to-r from-brand-blue to-brand-red text-white py-3 rounded-lg hover:opacity-90 transition-colors flex items-center justify-center">
                        <i class="fas fa-play mr-2"></i> Reproducir
                    </button>
                </div>
            </div>
        </div>

        <!-- Descripción -->
        <div class="mt-6 p-6 border-t border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Descripción</h2>
            <div class="prose max-w-none text-gray-600">{!! $radio->description !!}</div>
        </div>
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
        fetch(currentTrackUrl)
            .then(response => response.json())
            .then(data => {
                // Actualizar la canción actual si hay datos
                if (data.current_track) {
                    document.getElementById('current-track').textContent = data.current_track;
                } else {
                    document.getElementById('current-track').textContent = 'Información no disponible';
                }
                
                // Actualizar la cantidad de oyentes si hay datos
                if (data.listeners) {
                    document.getElementById('listeners').textContent = 'Oyentes: ' + data.listeners;
                }
            })
            .catch(error => {
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
