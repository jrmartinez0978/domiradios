<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
    @forelse($genres as $genre)
        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 group">
            <a href="{{ route('ciudades.show', ['slug' => $genre->slug]) }}" class="block">
                <div class="h-32 flex items-center justify-center mb-3 overflow-hidden bg-gray-50 rounded-lg p-2">
                    <!-- Verificar si el género tiene una imagen, de lo contrario mostrar una imagen por defecto -->
                    <img src="{{ $genre->img ? Storage::url($genre->img) : asset('images/default-image.jpg') }}" 
                         alt="{{ $genre->name }}" 
                         class="mx-auto max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                </div>
                <h3 class="text-center font-medium text-gray-800 group-hover:text-brand-red transition-colors mb-3">{{ $genre->name }}</h3>
                
                <div class="mt-4 flex justify-center">
                    <span class="inline-flex items-center justify-center bg-gradient-to-r from-brand-blue to-brand-red text-white py-2 px-4 rounded-lg group-hover:opacity-90 transition-opacity text-sm">
                        <i class="fas fa-broadcast-tower mr-2"></i> Ver emisoras
                    </span>
                </div>
            </a>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500 text-xl">
                @if(empty($searchTerm))
                    No hay ciudades o géneros para mostrar.
                @else
                    No se encontraron ciudades o géneros que coincidan con "{{ $searchTerm }}".
                @endif
            </p>
        </div>
    @endforelse
</div>
