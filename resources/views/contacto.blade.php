@extends('layouts.dark')

@section('title', 'Contacto - Envía tu emisora - Domiradios')
@section('meta_description', 'Formulario de contacto para enviar tu emisora de radio a nuestro directorio.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="card p-6 md:p-8">
        <h1 class="text-3xl font-bold mb-3 text-gray-800 flex items-center">
            <span class="text-primary mr-3"><i class="fas fa-broadcast-tower"></i></span>
            Envía tu emisora de radio
        </h1>
        <p class="text-gray-500 mb-8">¿Tienes una emisora de radio que quieres incluir en nuestro directorio? Completa el siguiente formulario.</p>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-6 rounded-xl" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('contacto.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-5">
                    <h2 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-2">Información de contacto</h2>
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-600 mb-1.5">Nombre completo *</label>
                        <input type="text" name="nombre" id="nombre" required class="input-light w-full">
                        @error('nombre') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-600 mb-1.5">Correo electrónico *</label>
                        <input type="email" name="email" id="email" required class="input-light w-full">
                        @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-600 mb-1.5">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" class="input-light w-full">
                    </div>
                </div>
                <div class="space-y-5">
                    <h2 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-2">Información de la emisora</h2>
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-600 mb-1.5">Nombre de la emisora *</label>
                        <input type="text" name="name" id="name" required class="input-light w-full">
                        @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="bitrate" class="block text-sm font-medium text-gray-600 mb-1.5">Frecuencia *</label>
                        <input type="text" name="bitrate" id="bitrate" required placeholder="Ej: 95.7 FM" class="input-light w-full">
                        @error('bitrate') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="ciudad" class="block text-sm font-medium text-gray-600 mb-1.5">Ciudad *</label>
                        <input type="text" name="ciudad" id="ciudad" required class="input-light w-full">
                        @error('ciudad') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-5">
                    <h2 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-2">Enlaces y categorías</h2>
                    <div>
                        <label for="link_radio" class="block text-sm font-medium text-gray-600 mb-1.5">URL del stream *</label>
                        <input type="url" name="link_radio" id="link_radio" required placeholder="http://..." class="input-light w-full">
                        @error('link_radio') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="url_website" class="block text-sm font-medium text-gray-600 mb-1.5">Sitio web</label>
                        <input type="url" name="url_website" id="url_website" placeholder="http://..." class="input-light w-full">
                    </div>
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-600 mb-1.5">Géneros musicales *</label>
                        <input type="text" name="tags" id="tags" required placeholder="Merengue, Bachata, Salsa..." class="input-light w-full">
                        <p class="mt-1 text-xs text-gray-400">Separa los géneros con comas</p>
                        @error('tags') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="space-y-5">
                    <h2 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-2">Redes sociales</h2>
                    <div>
                        <label for="url_facebook" class="block text-sm font-medium text-gray-600 mb-1.5">Facebook</label>
                        <input type="url" name="url_facebook" id="url_facebook" placeholder="https://facebook.com/..." class="input-light w-full">
                    </div>
                    <div>
                        <label for="url_twitter" class="block text-sm font-medium text-gray-600 mb-1.5">Twitter</label>
                        <input type="url" name="url_twitter" id="url_twitter" placeholder="https://twitter.com/..." class="input-light w-full">
                    </div>
                    <div>
                        <label for="url_instagram" class="block text-sm font-medium text-gray-600 mb-1.5">Instagram</label>
                        <input type="url" name="url_instagram" id="url_instagram" placeholder="https://instagram.com/..." class="input-light w-full">
                    </div>
                </div>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-600 mb-1.5">Descripción de la emisora</label>
                <textarea name="description" id="description" rows="4" class="input-light w-full"></textarea>
            </div>
            <div>
                <label for="img" class="block text-sm font-medium text-gray-600 mb-1.5">Logo de la emisora (opcional)</label>
                <input type="file" name="img" id="img" accept="image/*" class="input-light w-full file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary file:font-semibold file:text-sm">
                <p class="mt-1 text-xs text-gray-400">Formato recomendado: JPG o PNG, tamaño máximo 2MB</p>
            </div>
            <div class="pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i> Enviar solicitud
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
