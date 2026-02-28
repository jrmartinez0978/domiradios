@extends('layouts.dark')

@section('title', 'Mis Favoritos - Domiradios')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="glass-card p-6 md:p-8">
        <h1 class="text-3xl font-bold mb-2 text-gray-100 flex items-center">
            <span class="text-accent-red mr-3"><i class="fas fa-heart"></i></span>
            Mis Emisoras Favoritas
        </h1>
        <p class="text-dark-400 mb-8">Aquí encontrarás todas tus emisoras favoritas para acceder rápidamente.</p>

        <div id="favoritos-list" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        </div>

        <div id="no-favorites-message" class="text-center py-16 hidden">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-glass-white-10 text-dark-500 mb-4">
                <i class="fas fa-heart-broken text-2xl"></i>
            </div>
            <p class="text-dark-400 text-lg">No tienes emisoras guardadas en tus favoritos.</p>
            <a href="{{ url('/') }}" class="inline-flex items-center mt-4 text-accent-red hover:text-red-400 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Explorar emisoras
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        if (favorites.length === 0) {
            document.getElementById('no-favorites-message').classList.remove('hidden');
        } else {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 5000);
            fetch('/api/favoritos', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ids: favorites }),
                signal: controller.signal
            })
            .then(response => { clearTimeout(timeoutId); if (!response.ok) throw new Error(); return response.json(); })
            .then(data => {
                if (data.length > 0) {
                    const container = document.getElementById('favoritos-list');
                    container.innerHTML = '';
                    data.forEach(radio => {
                        const card = document.createElement('div');
                        card.className = 'glass-card-hover group p-4';
                        const link = document.createElement('a');
                        link.href = '/emisoras/' + encodeURIComponent(radio.slug);
                        link.className = 'block';
                        const imgWrap = document.createElement('div');
                        imgWrap.className = 'aspect-square flex items-center justify-center mb-3 overflow-hidden bg-dark-800 rounded-xl';
                        const img = document.createElement('img');
                        img.src = '/storage/' + radio.img;
                        img.alt = radio.name;
                        img.className = 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300';
                        imgWrap.appendChild(img);
                        const title = document.createElement('h3');
                        title.className = 'text-center font-medium text-gray-200 group-hover:text-accent-red transition-colors mb-3 text-sm truncate';
                        title.textContent = radio.name;
                        link.appendChild(imgWrap);
                        link.appendChild(title);
                        const btn = document.createElement('button');
                        btn.className = 'w-full bg-accent-red/20 hover:bg-accent-red/30 text-red-300 py-2 px-3 rounded-lg transition-colors flex items-center justify-center text-sm';
                        btn.innerHTML = '<i class="fas fa-times mr-2"></i> Quitar';
                        btn.addEventListener('click', () => removeFromFavorites(String(radio.id)));
                        card.appendChild(link);
                        card.appendChild(btn);
                        container.appendChild(card);
                    });
                } else {
                    document.getElementById('no-favorites-message').classList.remove('hidden');
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                document.getElementById('favoritos-list').innerHTML = '';
                document.getElementById('no-favorites-message').classList.remove('hidden');
            });
        }
    });
    function removeFromFavorites(radioId) {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        favorites = favorites.filter(fav => fav !== radioId);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        location.reload();
    }
</script>
@endsection
