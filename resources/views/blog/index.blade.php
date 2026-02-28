@extends('layouts.dark')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        @php
        $breadcrumbs = [
            ['name' => 'Inicio', 'url' => url('/')],
            ['name' => 'Blog']
        ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />

        {{-- Header del Blog --}}
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Blog de Domiradios
            </h1>
            <p class="text-xl text-gray-500">
                Noticias, tutoriales y novedades sobre el mundo de la radio dominicana
            </p>
        </div>

        {{-- Posts Destacados --}}
        @if($featuredPosts->count() > 0)
            <section class="mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Posts Destacados
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($featuredPosts as $post)
                        <article class="card rounded-xl overflow-hidden transition-shadow duration-300">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                @if($post->featured_image)
                                    <img
                                        src="{{ $post->optimized_featured_image_url }}"
                                        alt="{{ $post->featured_image_alt ?? $post->title }}"
                                        class="w-full h-48 object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-primary/10 via-primary/5 to-primary/20 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-primary/30 text-4xl"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="p-6">
                                @if($post->category)
                                    <a href="{{ route('blog.category', $post->category) }}"
                                       class="inline-block px-3 py-1 bg-primary text-white text-sm rounded-full mb-3 hover:bg-primary-700 transition-colors">
                                        {{ $post->category }}
                                    </a>
                                @endif
                                <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-500 text-sm mb-4 line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ $post->formatted_published_date }}</span>
                                    <span>{{ $post->reading_time }} min lectura</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="grid md:grid-cols-3 gap-8">
            {{-- Posts Grid --}}
            <div class="md:col-span-2">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Últimos Artículos</h2>

                @if($posts->count() > 0)
                    <div class="space-y-6">
                        @foreach($posts as $post)
                            <article class="card rounded-xl overflow-hidden transition-shadow duration-300">
                                <div class="md:flex">
                                    <div class="md:w-1/3">
                                        <a href="{{ route('blog.show', $post->slug) }}">
                                            @if($post->featured_image)
                                                <img
                                                    src="{{ $post->optimized_featured_image_url }}"
                                                    alt="{{ $post->featured_image_alt ?? $post->title }}"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="w-full h-full min-h-[200px] bg-gradient-to-br from-primary/10 via-primary/5 to-primary/20 flex items-center justify-center">
                                                    <i class="fas fa-newspaper text-primary/30 text-4xl"></i>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="p-6 md:w-2/3">
                                        @if($post->category)
                                            <a href="{{ route('blog.category', $post->category) }}"
                                               class="inline-block px-3 py-1 bg-primary text-white text-sm rounded-full mb-3 hover:bg-primary-700 transition-colors">
                                                {{ $post->category }}
                                            </a>
                                        @endif
                                        <h3 class="text-2xl font-bold text-gray-800 mb-2">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary transition-colors">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-500 mb-4">
                                            {{ $post->excerpt }}
                                        </p>
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center space-x-4">
                                                <span>Por {{ $post->user->name }}</span>
                                                <span>{{ $post->formatted_published_date }}</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <span>{{ $post->reading_time }} min</span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    {{ $post->views }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="card rounded-xl p-8 text-center">
                        <p class="text-gray-500 text-lg">No hay posts publicados aún.</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                {{-- Buscador --}}
                <div class="card rounded-xl p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Buscar en el Blog</h3>
                    <form action="{{ route('blog.search') }}" method="GET">
                        <div class="relative">
                            <input
                                type="text"
                                name="q"
                                placeholder="Buscar artículos..."
                                class="w-full px-4 py-2 border border-surface-300 rounded-lg bg-white focus:ring-2 focus:ring-primary focus:border-transparent text-gray-800"
                                required
                            >
                            <button type="submit" class="absolute right-2 top-2 text-primary hover:text-primary-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Categorías --}}
                @if($categories->count() > 0)
                    <div class="card rounded-xl p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Categorías</h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blog.category', $category) }}"
                                       class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-primary-50 transition-colors text-gray-600 hover:text-primary">
                                        <span>{{ $category }}</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
