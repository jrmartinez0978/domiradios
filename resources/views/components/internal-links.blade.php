{{-- Internal Links Component - SEO contextual linking --}}
@props(['genres' => null, 'exclude' => null])

@php
    $popularGenres = $genres ?? \App\Models\Genre::withCount('radios')
        ->orderByDesc('radios_count')
        ->limit(8)
        ->get();

    if ($exclude) {
        $popularGenres = $popularGenres->where('slug', '!=', $exclude);
    }
@endphp

@if($popularGenres->count())
<section class="bg-gray-50 rounded-xl border border-gray-200 p-6 mt-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-compass text-primary mr-2"></i>
        Explora Más Emisoras
    </h3>
    <div class="flex flex-wrap gap-2">
        @foreach($popularGenres as $genre)
        <a href="{{ route('ciudades.show', $genre->slug) }}"
           wire:navigate
           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-full hover:border-primary/40 hover:text-primary transition-all duration-200">
            {{ $genre->name }}
        </a>
        @endforeach
        <a href="{{ route('ciudades.index') }}" wire:navigate class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary bg-white border border-primary/20 rounded-full hover:bg-primary hover:text-white transition-all duration-200">
            Ver todas <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</section>
@endif
