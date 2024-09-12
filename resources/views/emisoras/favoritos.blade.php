<!-- resources/views/emisoras/favoritos.blade.php -->
@extends('layouts.default')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold">Mis Emisoras Favoritas</h1>
    <div class="mt-4">
        <div id="favorites-list" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-4">
            <!-- Aquí se mostrarán las emisoras favoritas -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const favoritesList = document.getElementById('favorites-list');

        if (favorites.length === 0) {
            favoritesList.innerHTML = '<p>No tienes emisoras favoritas aún.</p>';
        } else {
            favorites.forEach(function(id) {
                fetch(`/emisoras/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const radioElement = `
                            <div class="bg-white p-4 rounded-lg shadow-md">
                                <img src="${data.img}" alt="${data.name}" class="w-full h-auto rounded-md mb-4">
                                <h3 class="text-center">${data.name}</h3>
                            </div>
                        `;
                        favoritesList.innerHTML += radioElement;
                    });
            });
        }
    });
</script>
@endsection


