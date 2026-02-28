@extends('layouts.app')

@section('canonical', $canonical_url)

@section('content')
    {{-- Schema.org Structured Data para el artículo --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ $post->title }}",
        "image": "{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('img/domiradios-logo-og.jpg') }}",
        "datePublished": "{{ $post->published_at->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "author": {
            "@type": "Person",
            "name": "{{ $post->user->name }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Domiradios",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('img/domiradios-logo-og.jpg') }}"
            }
        },
        "description": "{{ $post->excerpt ?? substr(strip_tags($post->content), 0, 160) }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ route('blog.show', $post->slug) }}"
        }
        @if($post->tags)
        ,"keywords": "{{ is_array($post->tags) ? implode(', ', $post->tags) : $post->tags }}"
        @endif
    }
    </script>

    <article class="container max-w-4xl mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2 text-gray-600">
                <li><a href="/" class="hover:text-brand-blue">Inicio</a></li>
                <li>/</li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-brand-blue">Blog</a></li>
                @if($post->category)
                    <li>/</li>
                    <li><a href="{{ route('blog.category', $post->category) }}" class="hover:text-brand-blue">{{ $post->category }}</a></li>
                @endif
                <li>/</li>
                <li class="text-gray-900 font-semibold">{{ Str::limit($post->title, 50) }}</li>
            </ol>
        </nav>

        {{-- Categoría y Fecha --}}
        <div class="flex items-center justify-between mb-4">
            @if($post->category)
                <a href="{{ route('blog.category', $post->category) }}"
                   class="inline-block px-4 py-2 bg-brand-blue text-white text-sm font-semibold rounded-full hover:bg-brand-red transition-colors">
                    {{ $post->category }}
                </a>
            @endif
            <div class="flex items-center space-x-4 text-sm text-gray-600">
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
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            {{ $post->title }}
        </h1>

        {{-- Autor --}}
        <div class="flex items-center mb-8 pb-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-brand-blue text-white rounded-full flex items-center justify-center text-xl font-bold mr-3">
                    {{ substr($post->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $post->user->name }}</p>
                    <p class="text-sm text-gray-600">Autor</p>
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
                    <figcaption class="text-sm text-gray-600 text-center mt-2 italic">
                        {{ $post->featured_image_alt }}
                    </figcaption>
                @endif
            </figure>
        @endif

        {{-- Extracto --}}
        @if($post->excerpt)
            <div class="text-xl text-gray-700 font-medium mb-8 p-6 bg-gray-50 rounded-xl border-l-4 border-brand-blue">
                {{ $post->excerpt }}
            </div>
        @endif

        {{-- Contenido Principal --}}
        <div class="prose prose-lg max-w-none mb-8">
            {!! strip_tags($post->content, '<p><br><b><strong><i><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6><img><figure><figcaption><blockquote><pre><code><table><thead><tbody><tr><th><td><div><span><hr>') !!}
        </div>

        {{-- Tags --}}
        @if($post->tags && is_array($post->tags) && count($post->tags) > 0)
            <div class="mb-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold mb-3 text-gray-900">Etiquetas:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}"
                           class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-brand-blue hover:text-white transition-colors">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Compartir en Redes Sociales --}}
        <div class="mb-12 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold mb-3 text-gray-900">Compartir:</h3>
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
            <section class="pt-8 border-t border-gray-200">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Artículos Relacionados</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
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
                                    <span class="inline-block px-3 py-1 bg-brand-blue text-white text-sm rounded-full mb-3">
                                        {{ $relatedPost->category }}
                                    </span>
                                @endif
                                <h3 class="text-lg font-bold mb-2 line-clamp-2">
                                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="hover:text-brand-blue transition-colors">
                                        {{ $relatedPost->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
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
               class="inline-flex items-center px-6 py-3 bg-brand-blue text-white font-semibold rounded-lg hover:bg-brand-red transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver al Blog
            </a>
        </div>
    </article>
@endsection
