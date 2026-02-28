<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    @forelse($genres as $genre)
        <a href="{{ route('ciudades.show', ['slug' => $genre->slug]) }}" wire:navigate class="card-hover group block p-4">
            <div class="aspect-square flex items-center justify-center mb-3 overflow-hidden bg-surface-100 rounded-xl">
                <img src="{{ $genre->img ? Storage::url($genre->img) : asset('images/default-image.jpg') }}"
                     alt="{{ $genre->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     loading="lazy">
            </div>
            <h3 class="text-center font-medium text-gray-600 group-hover:text-primary transition-colors mb-2 text-sm">{{ $genre->name }}</h3>
            <div class="flex justify-center">
                <span class="inline-flex items-center text-xs text-primary">
                    <i class="fas fa-broadcast-tower mr-1.5"></i> Ver emisoras
                </span>
            </div>
        </a>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-400 text-lg">
                @if(empty($searchTerm))
                    No hay ciudades o géneros para mostrar.
                @else
                    No se encontraron resultados para "{{ $searchTerm }}".
                @endif
            </p>
        </div>
    @endforelse
</div>
