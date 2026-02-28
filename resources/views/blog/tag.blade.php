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
                <li class="text-gray-900 font-semibold">#{{ $tag }}</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Etiqueta: #{{ $tag }}
            </h1>
            <p class="text-xl text-gray-600">
                Artículos etiquetados con "{{ $tag }}"
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            {{-- Posts Grid --}}
            <div class="md:col-span-2">
                @if($posts->count() > 0)
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
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-md p-8 text-center">
                        <p class="text-gray-600 text-lg">No hay posts con esta etiqueta.</p>
                        <a href="{{ route('blog.index') }}" class="inline-block mt-4 text-brand-blue hover:text-brand-red font-semibold">
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
                        <h3 class="text-xl font-bold mb-4">Categorías</h3>
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
