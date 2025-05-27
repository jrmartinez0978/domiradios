@extends('layouts.app')

@section('content')
<div class="container max-w-6xl py-12">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Imagen de la emisora -->
            <div class="md:w-1/3 bg-white p-8 flex items-center justify-center">
                <img src="{{ asset('storage/'.$radio->img) }}" alt="{{ $radio->nombre }}" class="w-full max-w-xs object-contain">
            </div>
            
            <!-- Información de la emisora -->
            <div class="md:w-2/3 p-8 bg-gradient-to-br from-brand-blue to-blue-800 text-white">
                <h1 class="text-3xl font-bold mb-4">{{ $radio->nombre }}</h1>
                
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    @if($radio->frecuencia)
                    <div>
                        <h3 class="text-lg font-semibold opacity-80">Frecuencia</h3>
                        <p class="text-xl">{{ $radio->frecuencia }}</p>
                    </div>
                    @endif
                    
                    @if($radio->ciudad)
                    <div>
                        <h3 class="text-lg font-semibold opacity-80">Ciudad</h3>
                        <p class="text-xl">{{ $radio->ciudad->nombre }}</p>
                    </div>
                    @endif
                    
                    @if($radio->genero)
                    <div>
                        <h3 class="text-lg font-semibold opacity-80">Género</h3>
                        <p class="text-xl">{{ $radio->genero }}</p>
                    </div>
                    @endif
                    
                    @if($radio->sitio_web)
                    <div>
                        <h3 class="text-lg font-semibold opacity-80">Sitio Web</h3>
                        <a href="{{ $radio->sitio_web }}" target="_blank" class="text-xl underline hover:text-brand-red">Visitar</a>
                    </div>
                    @endif
                </div>
                
                <!-- Reproductor de audio -->
                <div class="p-4 rounded-xl bg-white/10 backdrop-blur-sm">
                    <h3 class="text-lg font-semibold mb-3">Escuchar en vivo</h3>
                    <div id="player" class="w-full">
                        @if($radio->stream_url)
                        <audio id="audio-player" controls class="w-full">
                            <source src="{{ $radio->stream_url }}" type="audio/mp3">
                            Tu navegador no soporta la reproducción de audio.
                        </audio>
                        @else
                        <p class="text-sm">Stream no disponible en este momento.</p>
                        @endif
                    </div>
                </div>
                
                <!-- Botón de agregar a favoritos -->
                <div class="mt-6 flex">
                    <button id="addFavorite" class="flex items-center gap-2 bg-brand-red hover:bg-red-700 text-white py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-heart"></i> 
                        <span>Agregar a favoritos</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección de comentarios o información adicional -->
    @if($radio->descripcion)
    <div class="mt-12 bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-brand-blue mb-4">Acerca de esta emisora</h2>
        <div class="prose max-w-none">
            {{ $radio->descripcion }}
        </div>
    </div>
    @endif
    
    <!-- Emisoras relacionadas -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-brand-blue mb-6">Emisoras similares</h2>
        <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
            @foreach($emisoras_relacionadas as $emisora)
            <article class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow duration-300 flex flex-col">
                <img src="{{ asset('storage/'.$emisora->img) }}" alt="Logo {{ $emisora->nombre }}" class="w-full aspect-square object-contain rounded-t-2xl p-4" />
                <div class="p-5 flex-1">
                    <h3 class="font-semibold text-xl text-brand-blue">{{ $emisora->nombre }}</h3>
                    <p class="text-xs text-slate-500">
                        @if($emisora->frecuencia)
                        <span class="block">Frecuencia: {{ $emisora->frecuencia }}</span>
                        @endif
                        @if($emisora->ciudad)
                        <span class="block">Ciudad: {{ $emisora->ciudad->nombre }}</span>
                        @endif
                        @if($emisora->genero)
                        <span class="block">Género: {{ $emisora->genero }}</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('emisoras.show', $emisora->slug) }}" class="mx-5 mb-5 mt-0 inline-block text-center bg-emerald-600 text-white font-semibold rounded-lg py-2 hover:bg-emerald-700">
                    Escuchar
                </a>
            </article>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lógica para agregar a favoritos
        const addFavoriteBtn = document.getElementById('addFavorite');
        
        if (addFavoriteBtn) {
            addFavoriteBtn.addEventListener('click', function() {
                // Aquí iría la lógica para agregar a favoritos
                // Puedes usar fetch para una petición AJAX
                
                fetch('{{ route("agregar.favorito", $radio->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.innerHTML = '<i class="fas fa-heart"></i> <span>En favoritos</span>';
                        this.classList.remove('bg-brand-red', 'hover:bg-red-700');
                        this.classList.add('bg-green-600', 'hover:bg-green-700');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    });
</script>
@endpush
