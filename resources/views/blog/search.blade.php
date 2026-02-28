@extends('layouts.app')

@section('content')
    <div class="container max-w-7xl mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2 text-gray-600">
                <li><a href="/" class="hover:text-brand-blue">Inicio</a></li>
                <li>/</li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-brand-blue">Blog</a></li>
                <li>/</li>
                <li class="text-gray-900 font-semibold">Buscar</li>
            </ol>
        </nav>

        {{-- Header con búsqueda --}}
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Resultados de búsqueda
            </h1>
            <p class="text-xl text-gray-600 mb-6">
                Buscando: "<strong>{{ $query }}</strong>"
            </p>

            {{-- Formulario de búsqueda --}}
            <form action="{{ route('blog.search') }}" method="GET" class="max-w-2xl">
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ $query }}"
                        placeholder="Buscar artículos..."
                        class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                        required
                    >
                    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-brand-blue text-white rounded-lg hover:bg-brand-red transition-colors">
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            {{-- Posts Grid --}}
            <div class="md:col-span-2">
                @if($posts->count() > 0)
                    <p class="text-gray-600 mb-6">
                        Se encontraron <strong>{{ $posts->total() }}</strong> resultados
                    </p>

                    <div class="space-y-6">
                        @foreach($posts as $post)
                            <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <div class="md:flex">
                                    @if($post->featured_image)
                                        <div class="md:w-1/3">
                                            <a href="{{ route('blog.show', $post->slug) }}">
                                                <img
                                                    src="{{ $post->optimized_featured_image_url }}"
                                                    alt="{{ $post->featured_image_alt ?? $post->title }}"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                >
                                            </a>
                                        </div>
                                    @endif
                                    <div class="p-6 {{ $post->featured_image ? 'md:w-2/3' : 'w-full' }}">
                                        @if($post->category)
                                            <a href="{{ route('blog.category', $post->category) }}"
                                               class="inline-block px-3 py-1 bg-brand-blue text-white text-sm rounded-full mb-3 hover:bg-brand-red transition-colors">
                                                {{ $post->category }}
                                            </a>
                                        @endif
                                        <h3 class="text-2xl font-bold mb-2">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-brand-blue transition-colors">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-600 mb-4">
                                            {{ $post->excerpt }}
                                        </p>
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center space-x-4">
                                                <span>Por {{ $post->user->name }}</span>
                                                <span>{{ $post->formatted_published_date }}</span>
                                            </div>
                                            <span>{{ $post->reading_time }} min lectura</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-8">
                        {{ $posts->appends(['q' => $query])->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-md p-8 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 text-lg mb-2">No se encontraron resultados para "{{ $query }}"</p>
                        <p class="text-gray-500 mb-6">Intenta con otras palabras clave o navega por categorías</p>
                        <a href="{{ route('blog.index') }}" class="inline-block text-brand-blue hover:text-brand-red font-semibold">
                            Ver todos los artículos
                        </a>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                {{-- Volver al blog --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <a href="{{ route('blog.index') }}"
                       class="flex items-center text-brand-blue hover:text-brand-red font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver al Blog
                    </a>
                </div>

                {{-- Categorías --}}
                @if($categories->count() > 0)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold mb-4">Buscar por Categoría</h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blog.category', $category) }}"
                                       class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-100 transition-colors">
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
