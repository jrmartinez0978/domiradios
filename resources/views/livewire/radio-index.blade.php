<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <!-- Barra de búsqueda Livewire -->
            <input
                type="text"
                wire:model.live.500ms="search"
                class="w-full border border-gray-300 p-3 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue transition"
                placeholder="Buscar emisoras por nombre...">
        </div>

        @if($radios->count())
        <!-- Adaptación responsiva para la cuadrícula de tarjetas -->
        <div id="radio-list" class="mt-4 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($radios as $radio)
                <article class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 relative" itemscope itemtype="http://schema.org/RadioStation">
                    <!-- Badge superior para destacados -->
                    @if($radio->isFeatured)
                    <div class="absolute top-2 right-2 bg-gradient-to-r from-emerald-400 via-green-500 to-teal-600 text-white text-xs font-bold px-3 py-1.5 rounded-full z-10 shadow-md backdrop-blur-sm bg-opacity-90 transform hover:scale-105 transition-all duration-300 animate-pulse-slow">
                        <i class="fas fa-star text-yellow-300 mr-1"></i> Destacada
                    </div>
                    @endif
                    
                    <!-- Imagen cuadrada de la emisora con efecto hover -->
                    <div class="aspect-w-1 aspect-h-1 overflow-hidden group">
                        <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" aria-label="Ver detalles de {{ $radio->name }}">
                            <meta itemprop="url" content="{{ route('emisoras.show', ['slug' => $radio->slug]) }}">
                            <img 
                                src="{{ Storage::url($radio->img) }}" 
                                alt="{{ $radio->name }} - Emisora de radio {{ $radio->bitrate }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 lazyload"
                                itemprop="image"
                                loading="lazy"
                            >
                        </a>
                    </div>
                    
                    <!-- Contenido de la tarjeta -->
                    <div class="p-4">
                        <!-- Título con enlace a la página de detalles -->
                        <h2 class="text-xl font-bold mb-2 line-clamp-1" itemprop="name">
                            <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" class="hover:text-brand-blue transition-colors">
                                {{ $radio->name }}
                            </a>
                        </h2>
                        
                        <!-- Valoración con estrellas -->
                        <div class="flex items-center mb-3">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star {{ $i <= $radio->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600 ml-1" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                <meta itemprop="ratingValue" content="{{ $radio->rating }}">
                                <meta itemprop="bestRating" content="5">
                                <meta itemprop="worstRating" content="1">
                                <span itemprop="ratingCount">{{ number_format($radio->rating, 1) }}</span>
                            </span>
                        </div>
                        
                        <!-- Datos de la emisora con iconos -->
                        <div class="space-y-1 mb-3">
                            <p class="text-sm text-gray-600 flex items-center" itemprop="frequency">
                                <i class="fas fa-broadcast-tower text-brand-blue mr-2"></i> {{ $radio->bitrate }}
                            </p>
                            <p class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-map-marker-alt text-brand-red mr-2"></i> 
                                <span itemprop="location">{{ $radio->genres->pluck('name')->implode(', ') }}</span>
                            </p>
                            <p class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-music text-brand-blue mr-2"></i> 
                                <span itemprop="genre">{{ Str::of($radio->tags)->explode(',')->first() }}</span>
                            </p>
                        </div>
                        
                        <!-- Botón de reproducción con gradiente -->
                        <div class="mt-4">
                            <button
                                data-play-id="{{ $radio->id }}"
                                data-stream-url="{{ $radio->link_radio }}"
                                class="play-btn w-full bg-gradient-to-r from-emerald-500 to-green-600 text-white py-2 rounded-lg hover:shadow-lg transition-all duration-300 flex items-center justify-center font-medium"
                                itemprop="audio"
                                itemscope 
                                itemtype="http://schema.org/AudioObject"
                            >
                                <meta itemprop="contentUrl" content="{{ $radio->link_radio }}">
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-play mr-2"></i> Reproducir
                                </span>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $radios->links() }} <!-- Enlaces de paginación de Livewire -->
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500">No se encontraron emisoras que coincidan con tu búsqueda.</p>
        </div>
    @endif
    </div>
</div>

<!-- JavaScript para manejar la reproducción de radios -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables para controlar el estado actual
        let currentAudio = null;
        let currentButton = null;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 2;
        let reconnectTimeout = null;
        let hasRegisteredPlay = false; // Variable para evitar múltiples registros

        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        /**
         * Resetea el botón a su estado inicial (Reproducir).
         * @param {HTMLElement} button - El botón a resetear.
         */
        function resetButton(button) {
            // Limpiamos todas las clases de estado posibles
            button.classList.remove(
                'bg-gradient-to-r', 'from-red-500', 'to-red-600', 'from-yellow-500', 'to-orange-500', 
                'from-gray-500', 'to-gray-600', 'from-emerald-500', 'to-green-600',
                'hover:shadow-red-200', 'hover:shadow-yellow-200', 'hover:shadow-gray-200', 'hover:shadow-green-200'
            );
            
            // Aplicamos el estilo para "Reproducir"
            button.classList.add('bg-gradient-to-r', 'from-emerald-500', 'to-green-600', 'hover:shadow-green-200');
            
            // Actualizamos el contenido
            button.innerHTML = '<span class="flex items-center justify-center"><i class="fas fa-play mr-2"></i> Reproducir</span>';
        }

        /**
         * Cambia el botón al estado "Detener".
         * @param {HTMLElement} button - El botón a cambiar.
         */
        function setButtonToStop(button) {
            // Limpiamos todas las clases de estado posibles
            button.classList.remove(
                'bg-gradient-to-r', 'from-red-500', 'to-red-600', 'from-yellow-500', 'to-orange-500', 
                'from-gray-500', 'to-gray-600', 'from-emerald-500', 'to-green-600',
                'hover:shadow-red-200', 'hover:shadow-yellow-200', 'hover:shadow-gray-200', 'hover:shadow-green-200'
            );
            
            // Aplicamos el estilo para "Detener"
            button.classList.add('bg-gradient-to-r', 'from-red-500', 'to-red-600', 'hover:shadow-red-200');
            
            // Actualizamos el contenido
            button.innerHTML = '<span class="flex items-center justify-center"><i class="fas fa-stop mr-2"></i> Detener</span>';
        }

        /**
         * Cambia el botón al estado "Conectando".
         * @param {HTMLElement} button - El botón a cambiar.
         */
        function setButtonToConnecting(button) {
            // Limpiamos todas las clases de estado posibles
            button.classList.remove(
                'bg-gradient-to-r', 'from-red-500', 'to-red-600', 'from-yellow-500', 'to-orange-500', 
                'from-gray-500', 'to-gray-600', 'from-emerald-500', 'to-green-600',
                'hover:shadow-red-200', 'hover:shadow-yellow-200', 'hover:shadow-gray-200', 'hover:shadow-green-200'
            );
            
            // Aplicamos el estilo para "Conectando"
            button.classList.add('bg-gradient-to-r', 'from-yellow-500', 'to-orange-500', 'hover:shadow-yellow-200');
            
            // Actualizamos el contenido con un indicador de carga
            button.innerHTML = '<span class="flex items-center justify-center"><i class="fas fa-circle-notch fa-spin mr-2"></i> Conectando</span>';
        }

        /**
         * Cambia el botón al estado "Fuera de línea".
         * @param {HTMLElement} button - El botón a cambiar.
         */
        function setButtonToOffline(button) {
            // Limpiamos todas las clases de estado posibles
            button.classList.remove(
                'bg-gradient-to-r', 'from-red-500', 'to-red-600', 'from-yellow-500', 'to-orange-500', 
                'from-gray-500', 'to-gray-600', 'from-emerald-500', 'to-green-600',
                'hover:shadow-red-200', 'hover:shadow-yellow-200', 'hover:shadow-gray-200', 'hover:shadow-green-200'
            );
            
            // Aplicamos el estilo para "Fuera de línea"
            button.classList.add('bg-gradient-to-r', 'from-gray-500', 'to-gray-600', 'hover:shadow-gray-200');
            
            // Actualizamos el contenido
            button.innerHTML = '<span class="flex items-center justify-center"><i class="fas fa-exclamation-circle mr-2"></i> Fuera de línea</span>';
        }

        /**
         * Función para registrar la visita al hacer clic en reproducir
         * @param {string} radioId - El ID de la emisora
         */
        function registerPlay(radioId) {
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

        /**
         * Intenta reconectar la emisora.
         */
        function attemptReconnect() {
            if (reconnectAttempts >= maxReconnectAttempts) {
                // Excedido el número de intentos, marcar como fuera de línea
                setButtonToOffline(currentButton);
                currentAudio = null;
                currentButton = null;
                reconnectAttempts = 0;
                hasRegisteredPlay = false; // Reiniciar el registro de reproducción
                return;
            }

            reconnectAttempts++;
            reconnectTimeout = setTimeout(function() {
                if (currentAudio) {
                    setButtonToConnecting(currentButton);
                    currentAudio.play().then(() => {
                        setButtonToStop(currentButton);
                        reconnectAttempts = 0;

                        // Registrar la visita si aún no se ha hecho
                        if (!hasRegisteredPlay) {
                            registerPlay(currentAudio.radioId);
                            hasRegisteredPlay = true;
                        }
                    }).catch((error) => {
                        setButtonToConnecting(currentButton);
                        attemptReconnect();
                    });
                }
            }, 7000); // Esperar 7 segundos antes del siguiente intento
        }

        /**
         * Maneja el clic en un botón de reproducción.
         * @param {HTMLElement} button - El botón que fue clickeado.
         */
        function handlePlayButtonClick(button) {
            const radioId = button.getAttribute('data-play-id');
            const streamUrl = button.getAttribute('data-stream-url');

            // Reiniciar la variable hasRegisteredPlay
            hasRegisteredPlay = false;

            // Si otra emisora está sonando, pausarla y resetear su botón
            if (currentAudio && currentAudio.radioId !== radioId) {
                currentAudio.pause();
                if (currentButton) {
                    resetButton(currentButton);
                }
                clearTimeout(reconnectTimeout);
                currentAudio = null;
                currentButton = null;
                reconnectAttempts = 0;
                hasRegisteredPlay = false; // Reiniciar el registro de reproducción
            }

            // Si la misma emisora está sonando, pausarla y resetear el botón
            if (currentAudio && currentAudio.radioId === radioId && !currentAudio.paused) {
                currentAudio.pause();
                resetButton(button);
                currentAudio = null;
                currentButton = null;
                reconnectAttempts = 0;
                clearTimeout(reconnectTimeout);
                hasRegisteredPlay = false; // Reiniciar el registro de reproducción
                return;
            }

            // Crear un nuevo elemento de audio si es necesario
            if (!currentAudio || currentAudio.radioId !== radioId) {
                if (currentAudio) {
                    currentAudio.pause();
                    resetButton(currentButton);
                }

                currentAudio = new Audio(streamUrl);
                currentAudio.radioId = radioId;
                currentAudio.crossOrigin = 'anonymous';

                // Manejar eventos del elemento de audio
                currentAudio.addEventListener('canplay', function() {
                    // El stream está disponible, iniciar reproducción
                    currentAudio.play().then(() => {
                        setButtonToStop(currentButton);
                        reconnectAttempts = 0;

                        // Registrar la visita si aún no se ha hecho
                        if (!hasRegisteredPlay) {
                            registerPlay(radioId);
                            hasRegisteredPlay = true;
                        }
                    }).catch((error) => {
                        // Error al reproducir, intentar reconectar
                        setButtonToConnecting(currentButton);
                        attemptReconnect();
                    });
                });

                currentAudio.addEventListener('playing', function() {
                    setButtonToStop(currentButton);
                    
                    // Actualizar MediaSession para la pantalla de bloqueo
                    updateMediaSession(radioId);
                });

                currentAudio.addEventListener('pause', function() {
                    resetButton(currentButton);
                });

                currentAudio.addEventListener('error', function() {
                    // Error en la reproducción, intentar reconectar
                    setButtonToConnecting(currentButton);
                    attemptReconnect();
                });

                currentAudio.addEventListener('ended', function() {
                    resetButton(currentButton);
                });

                currentAudio.addEventListener('stalled', function() {
                    // La conexión se ha perdido, intentar reconectar
                    setButtonToConnecting(currentButton);
                    attemptReconnect();
                });
            }

            // Actualizar el botón actual y cambiar a "Conectando"
            currentButton = button;
            setButtonToConnecting(button);

            // Intentar reproducir el audio
            currentAudio.play().then(() => {
                setButtonToStop(button);
                reconnectAttempts = 0;

                // Registrar la visita si aún no se ha hecho
                if (!hasRegisteredPlay) {
                    registerPlay(radioId);
                    hasRegisteredPlay = true;
                }
            }).catch((error) => {
                setButtonToConnecting(button);
                attemptReconnect();
            });
        }

        // Delegación de eventos: manejar clicks en los botones Play/Stop
        document.getElementById('radio-list').addEventListener('click', function(event) {
            const button = event.target.closest('.play-btn');
            if (button) {
                handlePlayButtonClick(button);
            }
        });

        // Función para actualizar la MediaSession API (control en pantalla de bloqueo)
        function updateMediaSession(radioId) {
            if ('mediaSession' in navigator) {
                // Buscar la información de la radio por su ID
                const radioCard = document.querySelector(`[data-play-id="${radioId}"]`).closest('article');
                const radioName = radioCard.querySelector('[itemprop="name"]').textContent.trim();
                const radioImage = radioCard.querySelector('[itemprop="image"]').getAttribute('src');
                const radioGenre = radioCard.querySelector('.radio-genre') ? 
                                   radioCard.querySelector('.radio-genre').textContent.trim() : 
                                   'Radio en vivo';
                
                // Establecer los metadatos de la sesión multimedia
                navigator.mediaSession.metadata = new MediaMetadata({
                    title: radioName + ' - En vivo',
                    artist: radioName,
                    album: radioGenre,
                    artwork: [
                        { src: radioImage, sizes: '96x96', type: 'image/png' },
                        { src: radioImage, sizes: '128x128', type: 'image/png' },
                        { src: radioImage, sizes: '192x192', type: 'image/png' },
                        { src: radioImage, sizes: '256x256', type: 'image/png' },
                        { src: radioImage, sizes: '384x384', type: 'image/png' },
                        { src: radioImage, sizes: '512x512', type: 'image/png' }
                    ]
                });
                
                // Configurar los controladores de acciones para MediaSession
                navigator.mediaSession.setActionHandler('play', () => { 
                    if (currentAudio && currentButton) {
                        currentAudio.play();
                        setButtonToStop(currentButton);
                    }
                });
                
                navigator.mediaSession.setActionHandler('pause', () => {
                    if (currentAudio && currentButton) {
                        currentAudio.pause();
                        resetButton(currentButton);
                    }
                });
            }
        }
        
        // Manejar actualizaciones de Livewire para mantener la funcionalidad de los botones
        Livewire.hook('message.processed', (message, component) => {
            // No es necesario hacer nada aquí ya que usamos delegación de eventos
            // Los nuevos botones serán manejados por el event listener en el contenedor
        });
    });
</script>







