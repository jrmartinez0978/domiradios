@extends('layouts.default')

@section('title', $radio->name . ' - Escucha en vivo')
@section('meta_description', $radio->description)
@section('meta_keywords', $radio->tags)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/3 mb-4 md:mb-0">
                <img src="{{ Storage::url($radio->img) }}" alt="{{ $radio->name }}" class="w-full h-auto rounded-md shadow-lg">
            </div>
            <div class="w-full md:w-2/3 md:ml-6">
                <h1 class="text-4xl font-bold">{{ $radio->name }}</h1>
                <p class="text-gray-600 mt-2 text-lg">Frecuencia: {{ $radio->bitrate }}</p>
                <p class="text-gray-600 text-lg">Ciudad: {{ $radio->genres->pluck('name')->implode(', ') }}</p>

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
            </div>
        </div>

        <div class="mt-6">
            <h2 class="text-2xl font-bold">Descripción</h2>
            <p class="mt-2 text-gray-600">{{ $radio->description }}</p>
        </div>
    </div>

    <!-- Emisoras relacionadas -->
<div class="mt-8">
    <h2 class="text-lg font-semibold mb-4">Otras emisoras de {{ $radio->genres->pluck('name')->implode(', ') }}</h2>
    <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-6 mt-4">
        @foreach($relatedRadios as $related)
            <div class="bg-white p-4 rounded-lg shadow-md">
                <a href="{{ route('emisoras.show', $related->slug) }}">
                    <img src="{{ Storage::url($related->img) }}" alt="{{ $related->name }}" class="w-full h-auto rounded-md mb-4">
                    <h3 class="text-s">{{ $related->name }}</h3>
                </a>
            </div>
        @endforeach
        </div>
    </div>
</div>

<script>
    const audioPlayer = document.getElementById('audio-player');
    const playButton = document.getElementById('play-btn');
    let isPlaying = false;

    playButton.addEventListener('click', function () {
        if (isPlaying) {
            audioPlayer.pause();
            playButton.textContent = 'Reproducir';
            playButton.classList.remove('bg-red-600');
            playButton.classList.add('bg-green-600');
        } else {
            audioPlayer.play();
            playButton.textContent = 'Detener';
            playButton.classList.remove('bg-green-600');
            playButton.classList.add('bg-red-600');
        }
        isPlaying = !isPlaying;
    });
</script>
@endsection



