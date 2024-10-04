<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-6">
        <!-- Barra de búsqueda -->
        <input
            type="text"
            wire:model.live.500ms="search"
            class="w-full border border-gray-300 p-2 rounded mb-4"
            placeholder="Buscar emisoras por nombre...">
    </div>

    @if($radios->count())
        <!-- Adaptación responsiva para la cuadrícula de tarjetas -->
        <div id="radio-list" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($radios as $radio)
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <!-- Imagen cuadrada de la emisora -->
                    <div class="aspect-w-1 aspect-h-1">
                        <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}">
                            <img src="{{ Storage::url($radio->img) }}" alt="{{ $radio->name }}" class="w-full h-full object-cover rounded-md lazyload">
                        </a>
                    </div>
                    <!-- Título con enlace a la página de detalles -->
                    <h2 class="text-xl font-bold mt-4">
                        <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" class="hover:text-blue-500 transition-colors">
                            {{ $radio->name }}
                        </a>
                    </h2>
                    <p class="text-sm text-gray-600">Frecuencia: {{ $radio->bitrate }}</p>
                    <p class="text-sm text-gray-600">Ciudad: {{ $radio->genres->pluck('name')->implode(', ') }}</p>
                    <p class="text-sm text-gray-600">Género: {{ explode(',', $radio->tags)[0] }}</p>

                    <!-- Reproductor de audio con botón Play/Stop -->
                    <div class="mt-4">
                        <div class="mb-4">
                            <button
                                data-play-id="{{ $radio->id }}"
                                data-stream-url="{{ $radio->link_radio }}"
                                class="play-btn w-full bg-green-700 text-white py-2 rounded hover:bg-green-800">
                                Reproducir
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $radios->links() }} <!-- Enlaces de paginación de Livewire -->
        </div>
    @else
        <p>No se encontraron emisoras.</p>
    @endif
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

        /**
         * Resetea el botón a su estado inicial.
         * @param {HTMLElement} button - El botón a resetear.
         */
        function resetButton(button) {
            button.textContent = 'Reproducir';
            button.classList.remove('bg-red-700', 'bg-gray-500', 'bg-yellow-500', 'hover:bg-gray-600', 'hover:bg-red-800', 'hover:bg-yellow-600');
            button.classList.add('bg-green-700', 'hover:bg-green-800');
        }

        /**
         * Cambia el botón al estado "Detener".
         * @param {HTMLElement} button - El botón a cambiar.
         */
        function setButtonToStop(button) {
            button.textContent = 'Detener';
            button.classList.remove('bg-green-700', 'hover:bg-green-800', 'bg-gray-500', 'bg-yellow-500', 'hover:bg-gray-600', 'hover:bg-yellow-600');
            button.classList.add('bg-red-700', 'hover:bg-red-800');
        }

        /**
         * Cambia el botón al estado "Conectando".
         * @param {HTMLElement} button - El botón a cambiar.
         */
        function setButtonToConnecting(button) {
            button.textContent = 'Conectando';
            button.classList.remove('bg-green-700', 'hover:bg-green-800', 'bg-red-700', 'hover:bg-red-800', 'bg-gray-500');
            button.classList.add('bg-yellow-500', 'hover:bg-yellow-600');
        }

        /**
         * Cambia el botón al estado "Fuera de línea".
         * @param {HTMLElement} button - El botón a cambiar.
         */
        function setButtonToOffline(button) {
            button.textContent = 'Fuera de línea';
            button.classList.remove('bg-green-700', 'hover:bg-green-800', 'bg-red-700', 'hover:bg-red-800', 'bg-yellow-500', 'hover:bg-yellow-600');
            button.classList.add('bg-gray-500', 'hover:bg-gray-600');
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
                return;
            }

            reconnectAttempts++;
            reconnectTimeout = setTimeout(function() {
                if (currentAudio) {
                    setButtonToConnecting(currentButton);
                    currentAudio.play().then(() => {
                        setButtonToStop(currentButton);
                        reconnectAttempts = 0;
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
            }

            // Si la misma emisora está sonando, pausarla y resetear el botón
            if (currentAudio && currentAudio.radioId === radioId && !currentAudio.paused) {
                currentAudio.pause();
                resetButton(button);
                currentAudio = null;
                currentButton = null;
                reconnectAttempts = 0;
                clearTimeout(reconnectTimeout);
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
                    }).catch((error) => {
                        // Error al reproducir, intentar reconectar
                        setButtonToConnecting(currentButton);
                        attemptReconnect();
                    });
                });

                currentAudio.addEventListener('playing', function() {
                    setButtonToStop(currentButton);
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

        // Manejar actualizaciones de Livewire para mantener la funcionalidad de los botones
        Livewire.hook('message.processed', (message, component) => {
            // No es necesario hacer nada aquí ya que usamos delegación de eventos
            // Los nuevos botones serán manejados por el event listener en el contenedor
        });
    });
</script>


<!-- Microdatos estructurados para SEO con Schema.org -->
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ItemList",
      "itemListElement": [
        @foreach($radios as $index => $radio)
        {
          "@type": "ListItem",
          "position": {{ $index + 1 }},
          "item": {
            "@id": "{{ route('emisoras.show', ['slug' => $radio->slug]) }}",
            "name": "{{ $radio->name }}",
            "image": "{{ Storage::url($radio->img) }}",
            "description": "Escucha {{ $radio->name }} en {{ $radio->genres->pluck('name')->implode(', ') }}",
            "genre": "{{ $radio->tags }}"
          }
        }@if(!$loop->last),@endif
        @endforeach
      ]
    }
    </script>




