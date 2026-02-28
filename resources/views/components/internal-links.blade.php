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
<section class="glass-card p-6 mt-8">
    <h3 class="text-lg font-bold text-gray-100 mb-4 flex items-center">
        <i class="fas fa-compass text-accent-red mr-2"></i>
        Explora Más Emisoras
    </h3>
    <div class="flex flex-wrap gap-2">
        @foreach($popularGenres as $genre)
        <a href="{{ route('ciudades.show', $genre->slug) }}"
           class="btn-glass text-xs !px-3 !py-1.5 hover:border-accent-red/30">
            {{ $genre->name }}
        </a>
        @endforeach
        <a href="{{ route('ciudades.index') }}" class="btn-glass text-xs !px-3 !py-1.5 text-accent-red">
            Ver todas <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</section>
@endif
