@extends('layouts.admin')

@section('title', 'Editar Post')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.blog-posts.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Editar Post</h2>
            <p class="text-dark-300 text-sm mt-1">{{ $post->title }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.blog-posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-100 border-b border-glass-border pb-3">Contenido</h3>

                    <x-admin.form-input name="title" label="Titulo" required :value="$post->title" />
                    <x-admin.form-input name="slug" label="Slug" :value="$post->slug" />
                    <x-admin.form-textarea name="content" label="Contenido" rows="12" required :value="$post->content" id="content-editor" />
                    <x-admin.form-textarea name="excerpt" label="Extracto" rows="3" :value="$post->excerpt" />
                </div>

                {{-- SEO --}}
                <div class="glass-card p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-100 border-b border-glass-border pb-3">SEO</h3>

                    <x-admin.form-textarea name="meta_description" label="Meta Descripcion" rows="2" :value="$post->meta_description" />
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Publish Settings --}}
                <div class="glass-card p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-100 border-b border-glass-border pb-3">Publicacion</h3>

                    <x-admin.form-select name="status" label="Estado" :options="['draft' => 'Borrador', 'published' => 'Publicado', 'scheduled' => 'Programado']" :value="$post->status" required />
                    <x-admin.form-input name="published_at" label="Fecha de publicacion" type="datetime-local" :value="$post->published_at ? $post->published_at->format('Y-m-d\TH:i') : ''" />
                    <x-admin.form-input name="category" label="Categoria" :value="$post->category" />
                    <x-admin.form-input name="tags" label="Tags" :value="is_array($post->tags) ? implode(', ', $post->tags) : $post->tags" />
                </div>

                {{-- Featured Image --}}
                <div class="glass-card p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-100 border-b border-glass-border pb-3">Imagen Destacada</h3>

                    <x-admin.form-file-upload name="featured_image" label="Imagen" :currentImage="$post->featured_image" />
                    <x-admin.form-input name="featured_image_alt" label="Texto alternativo" :value="$post->featured_image_alt" />
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Actualizar Post
                </button>
            </div>
        </div>
    </form>
@endsection
