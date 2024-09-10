<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <!-- Caja de búsqueda -->
            <input
                type="text"
                wire:model="search"
                class="w-full border border-gray-300 p-2 rounded mb-4"
                placeholder="Buscar emisoras por nombre...">

            <!-- Lista de emisoras -->
            @if($radios->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($radios as $radio)
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                            <img src="{{ Storage::url($radio->img) }}" alt="{{ $radio->name }}" class="w-full h-32 object-cover rounded-md">
                            <h2 class="text-xl font-bold mt-4">{{ $radio->name }}</h2>
                            <p class="text-sm text-gray-600">Bitrate: {{ $radio->bitrate }}</p>
                            <p class="text-sm text-gray-600">Fuente: {{ $radio->source_radio }}</p>
                            <a href="{{ $radio->link_radio }}" target="_blank" class="text-blue-500 mt-2 inline-block">Escuchar en vivo</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No se encontraron emisoras.</p>
            @endif
        </div>
    </div>
</div>


