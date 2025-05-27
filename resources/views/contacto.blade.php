@extends('layouts.app')

@section('title', 'Contacto - Envía tu emisora - Domiradios')
@section('meta_description', 'Formulario de contacto para enviar tu emisora de radio a nuestro directorio. Añade tu estación al directorio más completo de emisoras dominicanas.')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100">
        <h1 class="text-3xl font-bold mb-3 text-gray-800 flex items-center">
            <span class="text-brand-blue mr-3"><i class="fas fa-broadcast-tower"></i></span>
            Envía tu emisora de radio
        </h1>
        <p class="text-gray-600 mb-8">¿Tienes una emisora de radio que quieres incluir en nuestro directorio? Completa el siguiente formulario y nos pondremos en contacto contigo lo antes posible.</p>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('contacto.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información de contacto -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2">Información de contacto</h2>
                    
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                        <input type="text" name="nombre" id="nombre" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
                        <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                    </div>
                </div>

                <!-- Información de la emisora -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2">Información de la emisora</h2>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la emisora *</label>
                        <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bitrate" class="block text-sm font-medium text-gray-700 mb-1">Frecuencia *</label>
                        <input type="text" name="bitrate" id="bitrate" required placeholder="Ej: 95.7 FM" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                        @error('bitrate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                        <input type="text" name="ciudad" id="ciudad" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                        @error('ciudad')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Enlaces y tags -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2">Enlaces y categorías</h2>
                    
                    <div>
                        <label for="link_radio" class="block text-sm font-medium text-gray-700 mb-1">URL del stream *</label>
                        <input type="url" name="link_radio" id="link_radio" required placeholder="http://..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                        @error('link_radio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="url_website" class="block text-sm font-medium text-gray-700 mb-1">Sitio web</label>
                        <input type="url" name="url_website" id="url_website" placeholder="http://..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Géneros musicales *</label>
                        <input type="text" name="tags" id="tags" required placeholder="Merengue, Bachata, Salsa..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                        <p class="mt-1 text-xs text-gray-500">Separa los géneros con comas</p>
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Redes sociales -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2">Redes sociales</h2>
                    
                    <div>
                        <label for="url_facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                        <input type="url" name="url_facebook" id="url_facebook" placeholder="https://facebook.com/..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                    </div>

                    <div>
                        <label for="url_twitter" class="block text-sm font-medium text-gray-700 mb-1">Twitter</label>
                        <input type="url" name="url_twitter" id="url_twitter" placeholder="https://twitter.com/..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                    </div>

                    <div>
                        <label for="url_instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                        <input type="url" name="url_instagram" id="url_instagram" placeholder="https://instagram.com/..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción de la emisora</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue"></textarea>
            </div>

            <div class="mt-6">
                <label for="img" class="block text-sm font-medium text-gray-700 mb-1">Logo de la emisora (opcional)</label>
                <input type="file" name="img" id="img" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-brand-blue/30 focus:border-brand-blue">
                <p class="mt-1 text-xs text-gray-500">Formato recomendado: JPG o PNG, tamaño máximo 2MB</p>
            </div>

            <div class="mt-8">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-brand-blue to-brand-red text-white font-medium rounded-lg hover:opacity-90 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i> Enviar solicitud
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
