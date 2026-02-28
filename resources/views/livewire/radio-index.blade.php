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
                <!-- Datos estructurados: address -->
                <meta itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"
                    @if(!empty($radio->address) || !empty($radio->city))
                        content="{{ !empty($radio->address) ? $radio->address : $radio->city }}, República Dominicana"
                    @else
                        content="República Dominicana"
                    @endif
                >
                <span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" hidden>
                    <meta itemprop="addressLocality" content="{{ !empty($radio->address) ? $radio->address : (!empty($radio->city) ? $radio->city : 'República Dominicana') }}">
                    <meta itemprop="addressCountry" content="República Dominicana">
                </span>
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
                            alt="{{ $radio->name }} {{ $radio->bitrate }} - Radio {{ Str::of($radio->tags)->explode(',')->first() }} en vivo - {{ $radio->genres->pluck('name')->implode(', ') }}, República Dominicana"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            itemprop="image"
                            width="300" height="300"
                            @if(isset($loop) && $loop->index >= 6) loading="lazy" @endif
                        >
                    </a>
                </div>

                <!-- Contenido de la tarjeta -->
                <div class="p-4">
                    <!-- Título con enlace a la página de detalles -->
                    <h3 class="text-xl font-bold mb-2 line-clamp-1" itemprop="name">
                        <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" class="hover:text-brand-blue transition-colors">
                            {{ $radio->name }}
                        </a>
                    </h3>

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
                            <meta itemprop="ratingCount" content="{{ isset($radio->rating_count) ? (int)$radio->rating_count : 1 }}">
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
                        @if($radio->source_radio === 'RTCStream')
                        @php
                            $pu = parse_url($radio->link_radio);
                            $rHost = $pu['host'] ?? 'live.rtcstreaming.com';
                            $rPort = $pu['port'] ?? 9000;
                            $rParts = array_filter(explode('/', $pu['path'] ?? ''));
                            $rSlug = end($rParts) ?: $radio->slug;
                        @endphp
                        <button
                            data-play-id="{{ $radio->id }}"
                            data-rtc-slug="{{ $rSlug }}"
                            data-rtc-server="{{ $rHost }}"
                            data-rtc-port="{{ $rPort }}"
                            class="rtc-play-btn w-full bg-gradient-to-r from-brand-blue to-brand-red text-white py-2 rounded-lg hover:shadow-lg transition-all duration-300 flex items-center justify-center font-medium"
                            itemprop="audio"
                            itemscope
                            itemtype="http://schema.org/AudioObject"
                        >
                            <meta itemprop="contentUrl" content="{{ $radio->link_radio }}">
                            <span class="flex items-center justify-center">
                                <i class="fas fa-play mr-2"></i> Reproducir
                            </span>
                        </button>
                        <div class="text-[9px] text-center mt-1 font-bold italic drop-shadow-sm text-transparent bg-clip-text bg-gradient-to-r from-brand-blue to-brand-red">
                            <i class="fas fa-info-circle mr-1"></i> Baja latencia con RTCStream
                        </div>
                        @else
                        <button
                            data-play-id="{{ $radio->id }}"
                            data-stream-url="{{ $radio->link_radio }}"
                            class="play-btn w-full bg-gradient-to-r from-brand-blue to-brand-red text-white py-2 rounded-lg hover:shadow-lg transition-all duration-300 flex items-center justify-center font-medium"
                            itemprop="audio"
                            itemscope
                            itemtype="http://schema.org/AudioObject"
                        >
                            <meta itemprop="contentUrl" content="{{ $radio->link_radio }}">
                            <span class="flex items-center justify-center">
                                <i class="fas fa-play mr-2"></i> Reproducir
                            </span>
                        </button>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
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
@push('scripts')
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
            // Fetch robusto con timeout de 5s
            const playController = new AbortController();
            const playTimeout = setTimeout(() => playController.abort(), 5000);
            fetch('{{ route("radio.register-play") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ radio_id: radioId }),
                signal: playController.signal
            })
            .then(response => {
                clearTimeout(playTimeout);
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            })
            .then(data => {
                console.log('Visita registrada:', data);
            })
            .catch(error => {
                clearTimeout(playTimeout);
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
                    if (!currentButton) return;
                    currentAudio.play().then(() => {
                        if (!currentButton) return;
                        setButtonToStop(currentButton);
                        reconnectAttempts = 0;
                        if (!hasRegisteredPlay) {
                            registerPlay(radioId);
                            hasRegisteredPlay = true;
                        }
                    }).catch((error) => {
                        if (!currentButton) return;
                        setButtonToConnecting(currentButton);
                        attemptReconnect();
                    });
                });

                currentAudio.addEventListener('playing', function() {
                    if (!currentButton) return;
                    setButtonToStop(currentButton);
                    updateMediaSession(radioId);
                });

                currentAudio.addEventListener('pause', function() {
                    if (currentButton) resetButton(currentButton);
                });

                currentAudio.addEventListener('error', function() {
                    if (!currentButton) return;
                    setButtonToConnecting(currentButton);
                    attemptReconnect();
                });

                currentAudio.addEventListener('ended', function() {
                    if (currentButton) resetButton(currentButton);
                });

                currentAudio.addEventListener('stalled', function() {
                    if (!currentButton) return;
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

        // Delegación de eventos: manejar clicks en los botones Play/Stop (HTML5)
        document.getElementById('radio-list').addEventListener('click', function(event) {
            const button = event.target.closest('.play-btn');
            if (button) {
                // Detener RTCStream si está sonando
                stopCurrentRTC();
                handlePlayButtonClick(button);
            }
        });

        // === RTCStream: variables globales ===
        let currentRTCPlayer = null;
        let currentRTCButton = null;
        let currentRTCAudio = null;
        let rtcScriptLoaded = false;

        /**
         * Detiene el player RTCStream activo (si hay alguno).
         */
        function stopCurrentRTC() {
            if (currentRTCPlayer) {
                currentRTCPlayer.disconnect();
                currentRTCPlayer = null;
            }
            if (currentRTCAudio) {
                currentRTCAudio.pause();
                currentRTCAudio.srcObject = null;
                currentRTCAudio = null;
            }
            if (currentRTCButton) {
                resetButton(currentRTCButton);
                currentRTCButton = null;
            }
        }

        /**
         * Detiene el player HTML5 activo (si hay alguno).
         */
        function stopCurrentHTML5() {
            if (currentAudio) {
                currentAudio.pause();
                if (currentButton) resetButton(currentButton);
                clearTimeout(reconnectTimeout);
                currentAudio = null;
                currentButton = null;
                reconnectAttempts = 0;
                hasRegisteredPlay = false;
            }
        }

        /**
         * Carga rtc-player-v2.js dinámicamente (una sola vez).
         */
        function loadRTCScript() {
            return new Promise((resolve, reject) => {
                if (window.RTCStreamPlayer) { resolve(); return; }
                if (rtcScriptLoaded) { resolve(); return; }
                const s = document.createElement('script');
                s.src = '/js/rtc-player-v2.js?v=2.0';
                s.onload = () => { rtcScriptLoaded = true; resolve(); };
                s.onerror = () => reject(new Error('Error cargando rtc-player-v2.js'));
                document.head.appendChild(s);
            });
        }

        /**
         * Maneja click en botón RTCStream.
         */
        async function handleRTCPlayClick(button) {
            const radioId = button.getAttribute('data-play-id');
            const rtcSlug = button.getAttribute('data-rtc-slug');
            const rtcServer = button.getAttribute('data-rtc-server');
            const rtcPort = parseInt(button.getAttribute('data-rtc-port'), 10);

            // Si la misma RTCStream está sonando, detenerla
            if (currentRTCPlayer && currentRTCPlayer.slug === rtcSlug && currentRTCButton) {
                stopCurrentRTC();
                return;
            }

            // Detener cualquier reproducción activa (HTML5 o RTC)
            stopCurrentHTML5();
            stopCurrentRTC();

            // Preparar UI
            currentRTCButton = button;
            setButtonToConnecting(button);

            // Crear AudioContext AHORA (durante el gesto del click) para no perderlo
            var AC = window.AudioContext || window.webkitAudioContext;
            if (AC && !window._rtcUserAudioCtx) {
                try {
                    window._rtcUserAudioCtx = new AC({ sampleRate: 48000 });
                    window._rtcUserAudioCtx.resume();
                } catch(e) {}
            }

            // Audio element global para RTC (crear aquí, durante el gesto)
            if (!currentRTCAudio) {
                currentRTCAudio = new Audio();
                currentRTCAudio.setAttribute('playsinline', '');
                currentRTCAudio.setAttribute('webkit-playsinline', '');
            }

            try {
                // Cargar scripts
                await loadRTCScript();
                await RTCStreamPlayer.loadMediasoup(rtcServer, rtcPort);

                // Crear player
                const player = RTCStreamPlayer.create({ slug: rtcSlug, serverUrl: rtcServer, port: rtcPort });
                currentRTCPlayer = player;

                player.on('stream', function(mediaStream) {
                    console.log('RTCStream card: Stream recibido');
                    currentRTCAudio.srcObject = mediaStream;
                    player.attachAudio(currentRTCAudio);
                    var ctx = player.getAudioContext();
                    if (ctx && ctx.state !== 'running') ctx.resume();
                    currentRTCAudio.play().then(function() {
                        if (currentRTCButton) setButtonToStop(currentRTCButton);
                        registerPlay(radioId);
                        updateMediaSession(radioId);
                    }).catch(function(e) {
                        console.error('RTCStream card: Error play():', e);
                        if (currentRTCButton) setButtonToOffline(currentRTCButton);
                    });
                });

                player.on('state', function(info) {
                    console.log('RTCStream card: Estado:', info.state, info.detail || '');
                    if (info.state === 'error' && currentRTCButton === button) {
                        setTimeout(function() {
                            if (currentRTCPlayer === player && player.state === 'error') {
                                if (currentRTCButton) setButtonToOffline(button);
                            }
                        }, 10000);
                    }
                    if (info.state === 'connecting' && currentRTCButton === button) {
                        setButtonToConnecting(button);
                    }
                });

                player.connect();

            } catch (e) {
                console.error('Error iniciando RTCStream:', e);
                if (currentRTCButton) setButtonToOffline(currentRTCButton);
                currentRTCPlayer = null;
                currentRTCButton = null;
            }
        }

        // Delegación de eventos: manejar clicks en botones RTCStream
        document.getElementById('radio-list').addEventListener('click', function(event) {
            const button = event.target.closest('.rtc-play-btn');
            if (button) {
                handleRTCPlayClick(button);
            }
        });

        // Función para actualizar la MediaSession API (control en pantalla de bloqueo)
        function updateMediaSession(radioId) {
            if ('mediaSession' in navigator) {
                const el = document.querySelector('[data-play-id="' + radioId + '"]');
                if (!el) return;
                const radioCard = el.closest('article');
                if (!radioCard) return;
                const radioName = (radioCard.querySelector('[itemprop="name"]') || {}).textContent || 'Radio';
                const imgEl = radioCard.querySelector('[itemprop="image"]');
                const radioImage = imgEl ? imgEl.getAttribute('src') : '';

                navigator.mediaSession.metadata = new MediaMetadata({
                    title: radioName + ' - En vivo',
                    artist: radioName,
                    album: 'Radio en vivo',
                    artwork: [
                        { src: radioImage, sizes: '96x96', type: 'image/png' },
                        { src: radioImage, sizes: '256x256', type: 'image/png' },
                        { src: radioImage, sizes: '512x512', type: 'image/png' }
                    ]
                });

                navigator.mediaSession.setActionHandler('play', function() {
                    if (currentRTCAudio && currentRTCButton) {
                        currentRTCAudio.play();
                        setButtonToStop(currentRTCButton);
                    } else if (currentAudio && currentButton) {
                        currentAudio.play();
                        setButtonToStop(currentButton);
                    }
                });

                navigator.mediaSession.setActionHandler('pause', function() {
                    if (currentRTCAudio && currentRTCButton) {
                        stopCurrentRTC();
                    } else if (currentAudio && currentButton) {
                        currentAudio.pause();
                        resetButton(currentButton);
                    }
                });
            }
        }

        // Manejar actualizaciones de Livewire para mantener la funcionalidad de los botones
        Livewire.hook('message.processed', (message, component) => {
            // Delegación de eventos, no se necesita re-bindear
        });
    });
</script>
@endpush
</div>
