
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-6">
        <!-- Barra de búsqueda -->
        <input
            type="text"
            wire:model.live="search"
            class="w-full border border-gray-300 p-2 rounded mb-4"
            placeholder="Buscar emisoras por nombre...">
    </div>

    @if($radios->count())
        <!-- Adaptación responsiva para la cuadrícula de tarjetas -->
<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-6">
    @foreach($radios as $radio)
        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <!-- Imagen cuadrada de la emisora -->
            <div class="aspect-w-1 aspect-h-1">
                <img src="{{ Storage::url($radio->img) }}" alt="{{ $radio->name }}" class="w-full h-full object-cover rounded-md">
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

                    <!-- Reproductor de audio con botón único Play/Stop -->
                    <div class="mt-4">
                        <audio id="player-{{ $radio->id }}" src="{{ $radio->link_radio }}"></audio>
                        <button
                            id="play-btn-{{ $radio->id }}"
                            class="w-full bg-green-700 text-white py-2 rounded hover:bg-green-800"
                            onclick="togglePlay({{ $radio->id }})">
                            Reproducir
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>No se encontraron emisoras.</p>
    @endif
</div>

<script>
    let currentPlaying = null; // Guarda el reproductor que está actualmente sonando

    function togglePlay(id) {
        const player = document.getElementById('player-' + id);
        const playBtn = document.getElementById('play-btn-' + id);

        // Si hay una emisora sonando y no es la misma, detiene la anterior
        if (currentPlaying && currentPlaying !== player) {
            currentPlaying.pause();
            currentPlaying.nextElementSibling.textContent = 'Play';  // Cambia el botón de la emisora anterior
            currentPlaying.nextElementSibling.classList.remove('bg-red-700');
            currentPlaying.nextElementSibling.classList.add('bg-green-700');
        }

        // Si el reproductor actual está sonando, lo detiene
        if (!player.paused) {
            player.pause();
            playBtn.textContent = 'Play';
            playBtn.classList.remove('bg-red-700');
            playBtn.classList.add('bg-green-700');
        } else {
            // Reproduce la emisora y actualiza el botón
            player.play();
            playBtn.textContent = 'Detener';
            playBtn.classList.remove('bg-green-700');
            playBtn.classList.add('bg-red-700');
            currentPlaying = player; // Guarda el reproductor actual como el que está sonando
        }
    }
</script>


