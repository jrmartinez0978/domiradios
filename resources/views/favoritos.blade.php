@extends('layouts.app')

@section('title', 'Mis Favoritos - Domiradios')

@section('content')
<div class="container max-w-7xl mx-auto px-4">
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100">
        <h1 class="text-3xl font-bold mb-2 text-gray-800 flex items-center">
            <span class="text-brand-red mr-3"><i class="fas fa-heart"></i></span>
            Mis Emisoras Favoritas
        </h1>
        <p class="text-gray-600 mb-8">Aquí encontrarás todas tus emisoras favoritas para acceder rápidamente.</p>

        <!-- Grid de emisoras favoritas -->
        <div id="favoritos-list" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            <!-- Aquí se mostrarán las emisoras favoritas -->
        </div>

        <!-- Mensaje si no hay emisoras favoritas -->
        <div id="no-favorites-message" class="text-center py-16 hidden">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <i class="fas fa-heart-broken text-2xl"></i>
            </div>
            <p class="text-gray-600 text-lg">No tienes emisoras guardadas en tus favoritos.</p>
            <a href="{{ url('/') }}" class="inline-flex items-center mt-4 text-brand-blue hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> Explorar emisoras
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

        // Verificar si hay favoritos
        if (favorites.length === 0) {
            document.getElementById('no-favorites-message').classList.remove('hidden');
        } else {
            // Llamada AJAX para obtener las emisoras favoritas
            fetch('/api/favoritos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: favorites })
            })
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let html = '';
                    data.forEach(radio => {
                        html += `
                            <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100 group">
                                <a href="/emisoras/${radio.slug}" class="block">
                                    <div class="h-32 flex items-center justify-center mb-3 overflow-hidden bg-gray-50 rounded-lg p-2">
                                        <img src="/storage/${radio.img}" alt="${radio.name}" class="mx-auto max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <h3 class="text-center font-medium text-gray-800 group-hover:text-brand-red transition-colors mb-3">${radio.name}</h3>
                                </a>
                                <button class="w-full bg-brand-red hover:bg-opacity-90 text-white py-2 px-3 rounded-lg transition-colors flex items-center justify-center" onclick="removeFromFavorites('${radio.id}')">
                                    <i class="fas fa-times mr-2"></i> Quitar de favoritos
                                </button>
                            </div>
                        `;
                    });
                    document.getElementById('favoritos-list').innerHTML = html;
                } else {
                    document.getElementById('no-favorites-message').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('no-favorites-message').classList.remove('hidden');
            });
        }
    });

    // Función para quitar emisoras de favoritos
    function removeFromFavorites(radioId) {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        favorites = favorites.filter(fav => fav !== radioId);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        location.reload(); // Recargar la página para reflejar los cambios
    }
</script>
@endsection



