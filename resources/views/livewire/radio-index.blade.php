<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Search bar --}}
        <div class="glass-card p-4 mb-6">
            <input
                type="text"
                wire:model.live.500ms="search"
                class="glass-input w-full"
                placeholder="Buscar emisoras por nombre...">
        </div>

        @if($radios->count())
        <div id="radio-list" class="mt-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($radios as $radio)
                <x-radio-card :radio="$radio" :loop="$loop" />
            @endforeach
        </div>

        <div class="mt-6">
            {{ $radios->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-glass-white-10 text-dark-500 mb-4">
                <i class="fas fa-search text-2xl"></i>
            </div>
            <p class="text-dark-400 text-lg">No se encontraron emisoras que coincidan con tu búsqueda.</p>
        </div>
        @endif
    </div>
</div>
