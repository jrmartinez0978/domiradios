@extends('layouts.admin')

@section('title', 'Nuevo Post')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.blog-posts.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Nuevo Post</h2>
            <p class="text-gray-500 text-sm mt-1">Crear una nueva publicacion</p>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.blog-posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Contenido</h3>

                    <x-admin.form-input name="title" label="Titulo" required placeholder="Titulo del post" />
                    <x-admin.form-input name="slug" label="Slug" placeholder="titulo-del-post" />
                    <x-admin.form-textarea name="content" label="Contenido" rows="12" required placeholder="Escribe el contenido del post..." id="content-editor" />
                    <x-admin.form-textarea name="excerpt" label="Extracto" rows="3" placeholder="Resumen breve del post..." />
                </div>

                {{-- SEO --}}
                <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">SEO</h3>

                    <x-admin.form-textarea name="meta_description" label="Meta Descripcion" rows="2" placeholder="Descripcion para motores de busqueda..." />
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Publish Settings --}}
                <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Publicacion</h3>

                    <x-admin.form-select name="status" label="Estado" :options="['draft' => 'Borrador', 'published' => 'Publicado', 'scheduled' => 'Programado']" value="draft" required />
                    <x-admin.form-input name="published_at" label="Fecha de publicacion" type="datetime-local" />
                    <x-admin.form-input name="category" label="Categoria" placeholder="Noticias, Tecnologia..." />
                    <x-admin.form-input name="tags" label="Tags" placeholder="tag1, tag2, tag3" />
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Imagen Destacada</h3>

                    <x-admin.form-file-upload name="featured_image" label="Imagen" />
                    <x-admin.form-input name="featured_image_alt" label="Texto alternativo" placeholder="Descripcion de la imagen" />
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Guardar Post
                </button>
            </div>
        </div>
    </form>
@endsection
