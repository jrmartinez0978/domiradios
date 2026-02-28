@extends('layouts.dark')

@section('canonical', $canonical_url)

@section('content')
    {{-- Schema.org Structured Data para el artículo --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BlogPosting",
        "headline": "{{ $post->title }}",
        "image": "{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('img/domiradios-logo-og.jpg') }}",
        "datePublished": "{{ $post->published_at->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "author": {
            "@@type": "Person",
            "name": "{{ $post->user->name }}"
        },
        "publisher": {
            "@@type": "Organization",
            "name": "Domiradios",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('img/domiradios-logo-og.jpg') }}"
            }
        },
        "description": "{{ $post->excerpt ?? substr(strip_tags($post->content), 0, 160) }}",
        "mainEntityOfPage": {
            "@@type": "WebPage",
            "@@id": "{{ route('blog.show', $post->slug) }}"
        },
        "wordCount": {{ str_word_count(strip_tags($post->content)) }},
        "articleSection": "{{ $post->category ?? 'General' }}",
        "speakable": {
            "@@type": "SpeakableSpecification",
            "cssSelector": ["h1", ".blog-excerpt"]
        }
        @if($post->tags)
        ,"keywords": "{{ is_array($post->tags) ? implode(', ', $post->tags) : $post->tags }}"
        @endif
    }
    </script>

    <article class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        {{-- Breadcrumb --}}
        @php
        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => url('/')],
            ['name' => 'Blog', 'url' => route('blog.index')],
        ];
        if ($post->category) {
            $breadcrumbItems[] = ['name' => $post->category, 'url' => route('blog.category', $post->category)];
        }
        $breadcrumbItems[] = ['name' => Str::limit($post->title, 50)];
        @endphp
        <x-breadcrumbs :items="$breadcrumbItems" />

        {{-- Categoría y Fecha --}}
        <div class="flex items-center justify-between mb-4">
            @if($post->category)
                <a href="{{ route('blog.category', $post->category) }}"
                   class="inline-block px-4 py-2 bg-primary text-white text-sm font-semibold rounded-full hover:bg-primary-700 transition-colors">
                    {{ $post->category }}
                </a>
            @endif
            <div class="flex items-center space-x-4 text-sm text-gray-500">
                <time datetime="{{ $post->published_at->toIso8601String() }}">
                    {{ $post->formatted_published_date }}
                </time>
                <span>·</span>
                <span>{{ $post->reading_time }} min lectura</span>
                <span>·</span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ $post->views }} vistas
                </span>
            </div>
        </div>

        {{-- Título --}}
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
            {{ $post->title }}
        </h1>

        {{-- Autor --}}
        <div class="flex items-center mb-8 pb-6 border-b border-surface-300">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center text-xl font-bold mr-3">
                    {{ substr($post->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $post->user->name }}</p>
                    <p class="text-sm text-gray-500">Autor</p>
                </div>
            </div>
        </div>

        {{-- Imagen Destacada --}}
        @if($post->featured_image)
            <figure class="mb-8 rounded-xl overflow-hidden">
                <img
                    src="{{ $post->optimized_featured_image_url }}"
                    alt="{{ $post->featured_image_alt ?? $post->title }}"
                    class="w-full h-auto"
                    itemprop="image"
                >
                @if($post->featured_image_alt)
                    <figcaption class="text-sm text-gray-500 text-center mt-2 italic">
                        {{ $post->featured_image_alt }}
                    </figcaption>
                @endif
            </figure>
        @endif

        {{-- Extracto --}}
        @if($post->excerpt)
            <div class="text-xl text-gray-600 font-medium mb-8 p-6 bg-surface-100 rounded-xl border-l-4 border-primary blog-excerpt">
                {{ $post->excerpt }}
            </div>
        @endif

        {{-- Contenido Principal --}}
        <div class="prose prose-lg max-w-none mb-8">
            {!! clean($post->content) !!}
        </div>

        {{-- Tags --}}
        @if($post->tags && is_array($post->tags) && count($post->tags) > 0)
            <div class="mb-8 pt-6 border-t border-surface-300">
                <h3 class="text-lg font-semibold mb-3 text-gray-800">Etiquetas:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}"
                           class="px-3 py-1 bg-surface-100 text-gray-600 text-sm rounded-full hover:bg-primary hover:text-white transition-colors">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Compartir en Redes Sociales --}}
        <div class="mb-12 pt-6 border-t border-surface-300">
            <h3 class="text-lg font-semibold mb-3 text-gray-800">Compartir:</h3>
            <div class="flex space-x-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-center justify-center w-10 h-10 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition-colors">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . route('blog.show', $post->slug)) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>

        {{-- Posts Relacionados --}}
        @if($relatedPosts->count() > 0)
            <section class="pt-8 border-t border-surface-300">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Artículos Relacionados</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <article class="card rounded-xl overflow-hidden transition-shadow duration-300">
                            @if($relatedPost->featured_image)
                                <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                    <img
                                        src="{{ $relatedPost->optimized_featured_image_url }}"
                                        alt="{{ $relatedPost->featured_image_alt ?? $relatedPost->title }}"
                                        class="w-full h-48 object-cover"
                                        loading="lazy"
                                    >
                                </a>
                            @endif
                            <div class="p-6">
                                @if($relatedPost->category)
                                    <span class="inline-block px-3 py-1 bg-primary text-white text-sm rounded-full mb-3">
                                        {{ $relatedPost->category }}
                                    </span>
                                @endif
                                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">
                                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="hover:text-primary transition-colors">
                                        {{ $relatedPost->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-500 text-sm mb-3 line-clamp-2">
                                    {{ $relatedPost->excerpt }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $relatedPost->formatted_published_date }}</span>
                                    <span>{{ $relatedPost->reading_time }} min</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- CTA para volver al blog --}}
        <div class="mt-12 text-center">
            <a href="{{ route('blog.index') }}"
               class="inline-flex items-center px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver al Blog
            </a>
        </div>
    </article>
@endsection
