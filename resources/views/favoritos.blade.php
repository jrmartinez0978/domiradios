@extends('layouts.default')

@section('title', 'Tus Emisoras Favoritas')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Tus Emisoras Favoritas</h1>

    <!-- Grid de emisoras favoritas -->
    <div id="favoritos-list" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-6">
        <!-- Aquí se mostrarán las emisoras favoritas -->
    </div>

    <!-- Mensaje si no hay emisoras favoritas -->
    <div id="no-favorites-message" class="text-center text-gray-600 hidden">
        <p>No tienes emisoras guardadas en tus favoritos.</p>
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
                            <div class="bg-white p-4 rounded-lg shadow-md">
                                <a href="/emisoras/${radio.slug}">
                                    <img src="/storage/${radio.img}" alt="${radio.name}" class="w-full h-auto rounded-md mb-4">
                                    <h3 class="text-center text-xl font-bold">${radio.name}</h3>
                                </a>
                                <button class="w-full bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition" onclick="removeFromFavorites('${radio.id}')">
                                    Eliminar
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



