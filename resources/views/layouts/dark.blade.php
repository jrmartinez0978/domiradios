<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Preconnect para Performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    {{-- Fonts & Icons --}}
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"></noscript>

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    @if(isset($radios) && count($radios) > 0 && isset($radios[0]->img))
        <link rel="preload" as="image" href="{{ Storage::url('radios/optimized/' . basename(pathinfo($radios[0]->img, PATHINFO_FILENAME)) . '.webp') }}" fetchpriority="high">
    @endif

    {{-- SEO Tags --}}
    @hasSection('static_seo_tags')
        @yield('static_seo_tags')
    @else
        {!! Artesaos\SEOTools\Facades\SEOTools::generate() !!}
    @endif

    {{-- Structured Data: Organization y WebSite --}}
    <script type="application/ld+json">
    [
      {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Domiradios",
        "url": "https://domiradios.com.do",
        "logo": "https://domiradios.com.do/img/domiradios-logo-og.jpg",
        "sameAs": [
          "https://www.facebook.com/domiradios",
          "https://twitter.com/domiradios"
        ],
        "address": {
          "@@type": "PostalAddress",
          "addressLocality": "Santo Domingo",
          "addressCountry": "República Dominicana"
        },
        "contactPoint": {
          "@@type": "ContactPoint",
          "contactType": "customer service",
          "url": "https://domiradios.com.do/contacto",
          "availableLanguage": "Spanish"
        }
      },
      {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "url": "https://domiradios.com.do",
        "name": "Domiradios",
        "description": "Directorio de emisoras de radio dominicanas en vivo",
        "inLanguage": "es",
        "potentialAction": {
          "@@type": "SearchAction",
          "target": "https://domiradios.com.do/buscar?q={search_term_string}",
          "query-input": "required name=search_term_string"
        }
      }
    ]
    </script>

    @yield('schema_org')

    {{-- Language alternates --}}
    <link rel="alternate" hreflang="es-do" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="es" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

    {{-- SEO Meta --}}
    <meta name="keywords" content="@yield('meta_keywords', 'radio dominicana, emisoras dominicanas, radio online, streaming radio, radio en vivo, música dominicana, radio RD')" />
    @yield('head_additional')
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif
    <meta name="author" content="Domiradios">
    <meta name="robots" content="index, follow">

    @stack('scripts')

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Critical inline styles for above-the-fold --}}
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #ffffff; color: #1f2937; }
        .top-bar-inline { background-color: #005046; color: #fff; }
        nav { background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); border-bottom: 1px solid #e5e7eb; }
    </style>

    @livewireStyles
    <link rel="stylesheet" href="/css/rtc-player.css?v={{ time() }}">
</head>
<body class="min-h-screen flex flex-col font-sans bg-white text-gray-800">
    {{-- Top Info Bar (like habidominicana) --}}
    <div class="top-bar hidden sm:block bg-primary text-white text-sm py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-center gap-6">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-broadcast-tower text-xs"></i>
                {{ \App\Models\Radio::where('isActive', true)->count() }}+ emisoras en vivo
            </span>
            <span class="hidden md:flex items-center gap-1.5">
                <i class="fas fa-envelope text-xs"></i>
                info@domiradios.com.do
            </span>
            <span class="hidden md:flex items-center gap-1.5">
                <i class="fas fa-map-marker-alt text-xs"></i>
                República Dominicana
            </span>
        </div>
    </div>

    {{-- Navigation (habidominicana format: logo left, links center, actions right) --}}
    <nav x-data="{ mobileOpen: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-surface-300 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                {{-- Logo (left) --}}
                <a href="{{ url('/') }}" wire:navigate class="flex items-center gap-2 flex-shrink-0">
                    <div class="text-2xl font-extrabold text-primary tracking-tight">
                        <span>DOMI</span><span class="border-b-2 border-primary">RADIOS</span>
                    </div>
                </a>

                {{-- Center Nav Links (like habidominicana) --}}
                <div class="hidden lg:flex items-center gap-1">
                    <a href="{{ url('/') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary transition-colors">Emisoras</a>
                    <a href="{{ route('ciudades.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary transition-colors">Ciudades</a>
                    <a href="{{ route('blog.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary transition-colors">Blog</a>
                    <a href="{{ route('about') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary transition-colors">Sobre Nosotros</a>
                    <a href="{{ route('contacto') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary transition-colors">Contacto</a>
                </div>

                {{-- Right Actions (like habidominicana: heart, contact btn) --}}
                <div class="hidden lg:flex items-center gap-3">
                    <a href="{{ route('favoritos') }}" wire:navigate class="w-10 h-10 rounded-full border border-surface-300 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary/30 transition-all" title="Favoritos">
                        <i class="fas fa-heart text-sm"></i>
                    </a>
                    <button @click="$dispatch('toggle-search')" class="w-10 h-10 rounded-full border border-surface-300 flex items-center justify-center text-gray-500 hover:text-primary hover:border-primary/30 transition-all" title="Buscar emisora">
                        <i class="fas fa-search text-sm"></i>
                    </button>
                    <a href="{{ route('contacto') }}" wire:navigate class="inline-flex items-center gap-2 bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-full hover:bg-primary-600 transition-all">
                        <i class="fas fa-broadcast-tower text-xs"></i>
                        Envía tu emisora
                    </a>
                </div>

                {{-- Mobile search + hamburger --}}
                <div class="flex items-center gap-1 lg:hidden">
                    <button @click="$dispatch('toggle-search')" class="p-2 rounded-lg text-gray-500 hover:text-primary hover:bg-primary-50 transition-colors" title="Buscar">
                        <i class="fas fa-search w-6 h-6 flex items-center justify-center"></i>
                    </button>
                </div>
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-primary hover:bg-primary-50 transition-colors">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden bg-white border-b border-surface-300">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ url('/') }}" wire:navigate class="block px-3 py-2.5 rounded-lg text-gray-700 hover:text-primary hover:bg-primary-50 transition-colors font-medium">Emisoras</a>
                <a href="{{ route('ciudades.index') }}" wire:navigate class="block px-3 py-2.5 rounded-lg text-gray-700 hover:text-primary hover:bg-primary-50 transition-colors font-medium">Ciudades</a>
                <a href="{{ route('blog.index') }}" wire:navigate class="block px-3 py-2.5 rounded-lg text-gray-700 hover:text-primary hover:bg-primary-50 transition-colors font-medium">Blog</a>
                <a href="{{ route('about') }}" wire:navigate class="block px-3 py-2.5 rounded-lg text-gray-700 hover:text-primary hover:bg-primary-50 transition-colors font-medium">Sobre Nosotros</a>
                <a href="{{ route('favoritos') }}" wire:navigate class="block px-3 py-2.5 rounded-lg text-gray-700 hover:text-primary hover:bg-primary-50 transition-colors font-medium">
                    <i class="fas fa-heart mr-2 w-5 text-center text-sm"></i>Favoritos
                </a>
                <a href="{{ route('contacto') }}" wire:navigate class="block px-3 py-2.5 rounded-lg text-white bg-primary text-center mt-2 font-medium">
                    <i class="fas fa-broadcast-tower mr-2"></i>Envía tu emisora
                </a>
            </div>
        </div>
    </nav>

    {{-- Search Overlay --}}
    <div x-data="{ open: false, query: '' }"
         @toggle-search.window="open = !open; if(open) setTimeout(() => document.getElementById('search-input')?.focus(), 150)"
         @keydown.escape.window="open = false">
        <div x-show="open"
             x-bind:style="open ? '' : 'display:none'"
             class="fixed inset-0 z-[60]"
             @click.self="open = false">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative flex items-start justify-center pt-20 px-4 h-full" @click.self="open = false">
                <div class="w-full max-w-2xl">
                    <form @submit.prevent="if(query.trim()) { open = false; Livewire.navigate('/?q=' + encodeURIComponent(query.trim())); }"
                          class="bg-white rounded-2xl shadow-2xl p-2 flex items-center gap-2">
                        <i class="fas fa-search text-gray-400 ml-4"></i>
                        <input id="search-input" x-model="query" type="text"
                               class="flex-1 py-3 px-2 text-lg text-gray-800 placeholder-gray-400 bg-transparent border-none outline-none focus:ring-0"
                               placeholder="Buscar emisora...">
                        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-medium hover:bg-primary-600 transition-colors">
                            Buscar
                        </button>
                    </form>
                    <p class="text-center text-white/60 text-sm mt-3">Presiona ESC para cerrar</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="flex-grow pb-24">
        @yield('hero')
        @yield('content')
    </main>

    {{-- Footer (mint/sage like habidominicana) --}}
    <footer class="bg-footer pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div class="md:col-span-1">
                    <div class="text-2xl font-extrabold text-primary mb-3">Domiradios</div>
                    <p class="text-primary-700 text-sm leading-relaxed">El directorio más completo de emisoras de radio dominicanas. Escucha en vivo las mejores estaciones de República Dominicana.</p>
                    <div class="flex gap-3 mt-4">
                        <a href="https://www.facebook.com/domiradios" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary hover:text-white hover:bg-primary transition-all">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/domiradios" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary hover:text-white hover:bg-primary transition-all">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>

                {{-- Navigation --}}
                <div>
                    <h4 class="font-semibold text-primary mb-4 text-sm uppercase tracking-wider">Navegación</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}" wire:navigate class="text-primary-700 hover:text-primary text-sm transition-colors">Inicio</a></li>
                        <li><a href="{{ route('ciudades.index') }}" wire:navigate class="text-primary-700 hover:text-primary text-sm transition-colors">Por Ciudad</a></li>
                        <li><a href="{{ route('blog.index') }}" wire:navigate class="text-primary-700 hover:text-primary text-sm transition-colors">Blog</a></li>
                        <li><a href="{{ route('favoritos') }}" wire:navigate class="text-primary-700 hover:text-primary text-sm transition-colors">Mis Favoritos</a></li>
                    </ul>
                </div>

                {{-- Legal --}}
                <div>
                    <h4 class="font-semibold text-primary mb-4 text-sm uppercase tracking-wider">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('terminos') }}" wire:navigate class="text-primary-700 hover:text-primary text-sm transition-colors">Términos y Condiciones</a></li>
                        <li><a href="{{ route('privacidad') }}" wire:navigate class="text-primary-700 hover:text-primary text-sm transition-colors">Política de Privacidad</a></li>
                        <li><a href="{{ route('about') }}" wire:navigate class="text-primary-700 hover:text-primary text-sm transition-colors">Sobre Nosotros</a></li>
                        <li><a href="{{ route('contacto') }}" wire:navigate class="text-primary-700 hover:text-primary text-sm transition-colors">Contacto</a></li>
                    </ul>
                </div>

                {{-- Contact info --}}
                <div>
                    <h4 class="font-semibold text-primary mb-4 text-sm uppercase tracking-wider">Contacto</h4>
                    <ul class="space-y-2 text-sm text-primary-700">
                        <li class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-primary"></i> Santo Domingo, RD</li>
                        <li class="flex items-center gap-2"><i class="fas fa-envelope text-primary"></i> info@domiradios.com.do</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="bg-primary">
            <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-white/80">
                <span>&copy; {{ date('Y') }} Domiradios. Todos los derechos reservados.</span>
                <a href="https://rtcstreaming.com" target="_blank" rel="nofollow noopener noreferrer" class="text-white/60 hover:text-white transition-colors">Directorio de RTCStream</a>
            </div>
        </div>
    </footer>

    {{-- Player Bar Global (persisted across SPA navigations) --}}
    @persist('player-bar')
        <x-player-bar />
    @endpersist

    {{-- Rating System Script --}}
    <script>
        document.addEventListener('livewire:navigated', function() {
            const ratingElements = document.querySelectorAll('.user-rating');
            ratingElements.forEach(function(ratingElement) {
                const radioId = ratingElement.getAttribute('data-radio-id');
                const stars = ratingElement.querySelectorAll('.rating-star');
                const ctrl = new AbortController();
                const t = setTimeout(() => ctrl.abort(), 5000);
                fetch(`/emisoras/user-rating/${radioId}`, { signal: ctrl.signal })
                    .then(r => { clearTimeout(t); if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
                    .then(data => { if (data.rating > 0) highlightStars(stars, data.rating); })
                    .catch(() => clearTimeout(t));
                stars.forEach(function(star) {
                    star.addEventListener('mouseover', function() { highlightStars(stars, parseInt(this.dataset.rating)); });
                    star.addEventListener('mouseout', function() { resetStars(stars, ratingElement); });
                    star.addEventListener('click', function() { submitRating(radioId, parseInt(this.dataset.rating), stars, ratingElement); });
                });
            });
            function highlightStars(stars, rating) {
                stars.forEach((s, i) => { s.classList.toggle('text-yellow-400', i < rating); s.classList.toggle('text-gray-300', i >= rating); });
            }
            function resetStars(stars, el) {
                const cur = parseFloat(el.dataset.currentRating);
                stars.forEach((s, i) => { s.classList.toggle('text-yellow-400', i < cur); s.classList.toggle('text-gray-300', i >= cur); });
            }
            function submitRating(radioId, rating, stars, el) {
                const ctrl = new AbortController();
                const t = setTimeout(() => ctrl.abort(), 5000);
                fetch('/emisoras/rate', {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ radio_id: radioId, rating: rating }), signal: ctrl.signal
                }).then(r => { clearTimeout(t); if (!r.ok) throw new Error(); return r.json(); })
                .then(data => { if (data.success) { el.dataset.currentRating = data.new_rating; highlightStars(stars, rating); showNotification('Gracias por tu valoración', 'success'); } })
                .catch(() => { clearTimeout(t); showNotification('Error al enviar valoración', 'error'); });
            }
            function showNotification(msg, type) {
                const n = document.createElement('div');
                n.className = `fixed bottom-28 right-4 z-[60] px-4 py-3 rounded-xl text-sm font-medium text-white ${type === 'success' ? 'bg-emerald-500' : 'bg-red-500'} shadow-lg`;
                n.textContent = msg;
                document.body.appendChild(n);
                setTimeout(() => n.remove(), 3000);
            }
        });
    </script>

    @livewireScripts
</body>
</html>
