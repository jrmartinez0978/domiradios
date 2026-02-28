<div>
    {{-- Featured Section --}}
    @if($featured->count() > 0)
    <div class="mt-2">
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="inline-block px-3 py-1 bg-accent text-white text-xs font-semibold rounded-full mb-2">DESTACADAS</span>
                <h2 class="text-2xl font-bold text-primary">Emisoras Destacadas</h2>
                <p class="text-gray-400 mt-1 text-sm">Las emisoras más populares de República Dominicana</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($featured as $radio)
                @if($radio->source_radio === 'RTCStream')
                    <div class="col-span-2">
                        <x-radio-card :radio="$radio" :loop="$loop" />
                    </div>
                @else
                    <x-radio-card :radio="$radio" :loop="$loop" />
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- All Radios Section (hybrid: RTCStream span 2, rest span 1) --}}
    @if($radios->count())
    <div class="mt-12">
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="inline-block px-3 py-1 bg-primary text-white text-xs font-semibold rounded-full mb-2">DIRECTORIO</span>
                <h2 class="text-2xl font-bold text-primary">
                    @if($search || $genre)
                        Resultados de búsqueda
                    @else
                        Todas las Emisoras
                    @endif
                </h2>
                <p class="text-gray-400 mt-1 text-sm">
                    @if($search || $genre)
                        {{ $radios->total() }} emisoras encontradas
                    @else
                        Explora nuestro directorio completo de emisoras dominicanas
                    @endif
                </p>
            </div>
        </div>

        <div id="radio-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($radios as $radio)
                @if($radio->source_radio === 'RTCStream')
                    <div class="col-span-2">
                        <x-radio-card :radio="$radio" :loop="$loop" />
                    </div>
                @else
                    <x-radio-card :radio="$radio" :loop="$loop" />
                @endif
            @endforeach
        </div>

        <div class="mt-8">
            {{ $radios->links() }}
        </div>
    </div>
    @else
    <div class="text-center py-20 mt-12">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-surface-100 text-gray-300 mb-6">
            <i class="fas fa-search text-3xl"></i>
        </div>
        <p class="text-gray-500 text-xl font-medium">No se encontraron emisoras</p>
        <p class="text-gray-400 mt-2">Intenta con otro término de búsqueda o género</p>
    </div>
    @endif
</div>
