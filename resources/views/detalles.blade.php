@extends('layouts.default')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/3 mb-4 md:mb-0">
                <img src="{{ Storage::url($radio->img) }}" alt="{{ $radio->name }}" class="w-full h-auto rounded-md shadow-lg lazyload">
            </div>
            <div class="w-full md:w-2/3 md:ml-6">
                <h1 class="text-4xl font-bold">{{ $radio->name }}</h1>
                <p class="text-gray-600 mt-2 text-lg">Frecuencia: {{ $radio->bitrate }}</p>
                <p class="text-gray-600 text-lg">Ciudad: {{ $radio->genres->pluck('name')->implode(', ') }}</p>
                <p class="text-gray-600 text-lg">Géneros: {{ $radio->tags }}</p>

                <!-- Información en tiempo real -->
                <div class="mt-4">
                    <div class="stations__station__track" title="En directo ahora" role="status">
                        <span id="current-track" class="text-xl font-semibold">Cargando canción...</span>
                    </div>
                    <ul class="stations__station__metric mt-2 flex space-x-4" role="status">
                        <li id="listeners" class="i-listeners" title="radioescuchas">Oyentes: 0</li>
                        <li id="rating" class="i-chart" title="clasificación">Clasificación: {{ $radio->rating }}</li>
                    </ul>
                </div>

                <!-- Reproductor de audio con botón único Play/Stop -->
                <div class="mt-4">
                    <audio id="audio-player" src="{{ $radio->link_radio }}"></audio>
                    <button id="play-btn" class="w-full md:w-auto bg-green-600 text-white px-16 py-2 rounded-full hover:bg-green-700 transition">
                        Reproducir
                    </button>
                </div>

                <!-- Redes sociales -->
                <div class="mt-4 flex space-x-4">
                    @if($radio->url_website)
                    <a href="{{ $radio->url_website }}" target="_blank" class="text-blue-500 hover:underline">
                        <i class="fas fa-globe text-2xl"></i>
                        <span class="block text-xs">Website</span>
                    </a>
                    @endif
                    @if($radio->url_facebook)
                    <a href="{{ $radio->url_facebook }}" target="_blank" class="text-blue-700 hover:underline">
                        <i class="fab fa-facebook-f text-2xl"></i>
                        <span class="block text-xs">Facebook</span>
                    </a>
                    @endif
                    @if($radio->url_twitter)
                    <a href="{{ $radio->url_twitter }}" target="_blank" class="text-blue-400 hover:underline">
                        <i class="fab fa-twitter text-2xl"></i>
                        <span class="block text-xs">Twitter</span>
                    </a>
                    @endif
                    @if($radio->url_instagram)
                    <a href="{{ $radio->url_instagram }}" target="_blank" class="text-pink-500 hover:underline">
                        <i class="fab fa-instagram text-2xl"></i>
                        <span class="block text-xs">Instagram</span>
                    </a>
                    @endif
                </div>

                <!-- Guardar en Favoritos y Clasificación -->
                <div class="mt-6 flex items-center">
                    <button id="fav-btn" class="w-full md:w-auto bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700 transition">
                        Agregar a Favoritos
                    </button>
                    <div class="ml-4">
                        <span class="text-gray-600">Clasificación:</span>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= $radio->rating ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Descripción -->
        <div class="mt-6">
            <h2 class="text-2xl font-bold">Descripción</h2>
            <p class="mt-2 text-gray-600">{!! $radio->description !!}</p>
        </div>
    </div>

    <!-- Emisoras relacionadas -->
    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-4">Otras emisoras de {{ $radio->genres->pluck('name')->implode(', ') }}</h2>
        <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-4 mt-4">
            @foreach($relatedRadios as $related)
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <a href="{{ route('emisoras.show', $related->slug) }}">
                        <img src="{{ Storage::url($related->img) }}" alt="{{ $related->name }}" class="w-full h-auto rounded-md mb-4 lazyload">
                        <h3 class="text-center text-s">{{ $related->name }}</h3>
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

    // Función para redirigir a favoritos
    function redirectToFavorites() {
        window.location.href = '/favoritos';
    }

    // Función para actualizar el estado del botón
    function updateFavButton() {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const isFavorite = favorites.includes(radioId);

        if (isFavorite) {
            favButton.textContent = 'Ver en Favoritos';
            favButton.classList.remove('bg-green-600');
            favButton.classList.add('bg-red-600');
            favButton.onclick = redirectToFavorites;
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
            favorites = favorites.filter(fav => fav !== radioId);
        } else {
            // Agregar a favoritos
            favorites.push(radioId);
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
                if (data.error) {
                    document.getElementById('current-track').innerText = 'Error al obtener la canción';
                    document.getElementById('listeners').innerText = 'Oyentes: N/A';
                    console.error('Error al obtener los datos:', data.error);
                    return;
                }

                document.getElementById('current-track').innerText = data.currentTrack || 'Sin información';
                document.getElementById('listeners').innerText = `Oyentes: ${data.listeners}`;
            })
            .catch(error => {
                document.getElementById('current-track').innerText = 'Error al obtener la canción';
                document.getElementById('listeners').innerText = 'Oyentes: N/A';
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
        let connectionAttempts = 0;
        const maxAttempts = 4;
        let isTryingToPlay = false;
        let hasRegisteredPlay = false;

        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Función para reproducir o pausar el audio
        function toggleAudio() {
            if (audioPlayer.paused && !isTryingToPlay) {
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
