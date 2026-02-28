<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Meta tags básicos --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Preconnect CRÍTICO para Performance - debe estar lo más arriba posible --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    {{-- Preload recursos críticos para LCP (Largest Contentful Paint) --}}
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"></noscript>

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    @if(isset($radios) && count($radios) > 0 && isset($radios[0]->img))
        <link rel="preload" as="image" href="{{ Storage::url('radios/optimized/' . basename(pathinfo($radios[0]->img, PATHINFO_FILENAME)) . '.webp') }}">
    @endif

    {{-- SEO Tags: SEOTools genera title, description, canonical y OG tags automáticamente --}}
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
        }
      },
      {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "url": "https://domiradios.com.do",
        "name": "Domiradios",
        "potentialAction": {
          "@@type": "SearchAction",
          "target": "https://domiradios.com.do/buscar?q={search_term_string}",
          "query-input": "required name=search_term_string"
        }
      }
    ]
    </script>

    {{-- Language alternates para SEO internacional (futuro) --}}
    <link rel="alternate" hreflang="es-do" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="es" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

    <!-- Scripts adicionales -->
    @stack('scripts')
    
    <!-- Sistema de valoración para emisoras -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sistema de valoración de emisoras
            const ratingElements = document.querySelectorAll('.user-rating');
            
            ratingElements.forEach(function(ratingElement) {
                const radioId = ratingElement.getAttribute('data-radio-id');
                const stars = ratingElement.querySelectorAll('.rating-star');
                
                // Verificar si el usuario ya ha valorado esta emisora
                // Fetch robusto con timeout de 5s
                const ratingController = new AbortController();
                const ratingTimeout = setTimeout(() => ratingController.abort(), 5000);
                fetch(`/emisoras/user-rating/${radioId}`, { signal: ratingController.signal })
                    .then(response => {
                        clearTimeout(ratingTimeout);
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        return response.json();
                    })
                    .then(data => {
                        if (data.rating > 0) {
                            highlightStars(stars, data.rating);
                        }
                    })
                    .catch(error => {
                        clearTimeout(ratingTimeout);
                        console.error('Error al cargar la valoración del usuario:', error);
                    });
                
                // Agregar eventos de hover para previsualizar la valoración
                stars.forEach(function(star) {
                    star.addEventListener('mouseover', function() {
                        const rating = parseInt(this.getAttribute('data-rating'));
                        highlightStars(stars, rating);
                    });
                    
                    star.addEventListener('mouseout', function() {
                        // Al salir, restaurar la valoración actual
                        resetStars(stars, ratingElement);
                    });
                    
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.getAttribute('data-rating'));
                        submitRating(radioId, rating, stars, ratingElement);
                    });
                });
            });
            
            // Función para resaltar estrellas
            function highlightStars(stars, rating) {
                stars.forEach(function(star, index) {
                    if (index < rating) {
                        star.classList.add('text-yellow-400');
                        star.classList.remove('text-gray-300');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }
            
            // Función para restaurar estrellas a su estado original
            function resetStars(stars, ratingElement) {
                const currentRating = parseFloat(ratingElement.getAttribute('data-current-rating'));
                stars.forEach(function(star, index) {
                    if (index < currentRating) {
                        star.classList.add('text-yellow-400');
                        star.classList.remove('text-gray-300');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }
            
            // Función para enviar la valoración
            function submitRating(radioId, rating, stars, ratingElement) {
                // Fetch robusto con timeout de 5s
                const submitController = new AbortController();
                const submitTimeout = setTimeout(() => submitController.abort(), 5000);
                fetch('/emisoras/rate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        radio_id: radioId,
                        rating: rating
                    }),
                    signal: submitController.signal
                })
                .then(response => {
                    clearTimeout(submitTimeout);
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Actualizar el atributo de valoración actual
                        ratingElement.setAttribute('data-current-rating', data.new_rating);
                        
                        // Mostrar notificación de éxito
                        showNotification('¡Gracias por tu valoración!', 'success');
                        
                        // Actualizar la visualización de estrellas
                        highlightStars(stars, rating);
                    }
                })
                .catch(error => {
                    clearTimeout(submitTimeout);
                    showNotification('Error de red o servidor al enviar la valoración.', 'error');
                    console.error('Error al enviar la valoración:', error);
                });
            }
            
            // Función para mostrar notificaciones
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
                notification.innerHTML = message;
                document.body.appendChild(notification);
                
                // Eliminar la notificación después de 3 segundos
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        });
    </script>

    <!-- SEO Meta Tags -->
    <meta name="keywords" content="@yield('meta_keywords', 'radio dominicana, emisoras dominicanas, radio online, streaming radio, radio en vivo, música dominicana, radio RD, emisoras República Dominicana')" />
    
    <!-- Metadatos adicionales específicos de cada página -->
    @yield('head_additional')
    <meta name="author" content="Domiradios">
    <meta name="robots" content="index, follow">

    {{-- Open Graph y Twitter Card tags son generados automáticamente por SEOTools::generate() en línea 33 --}}

    {{-- Tailwind CSS Compilado Localmente - Optimizado para Performance --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts & Font Awesome ahora usan preload para mejor Core Web Vitals --}}

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F5F7FA;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' viewBox='0 0 1200 800' preserveAspectRatio='none'%3E%3Cdefs%3E%3ClinearGradient id='a' gradientUnits='userSpaceOnUse' x1='600' y1='25' x2='600' y2='777'%3E%3Cstop offset='0' stop-color='%23003A70' stop-opacity='0.07'/%3E%3Cstop offset='0.5' stop-color='%23003A70' stop-opacity='0.03'/%3E%3Cstop offset='1' stop-color='%23E21C25' stop-opacity='0.02'/%3E%3C/linearGradient%3E%3C/defs%3E%3Cpath fill='url(%23a)' d='M0 0h1200v800H0z'/%3E%3Cg %3E%3Cpath fill='%23FFFFFF' d='M1200 0L0 0 0 800 1200 800z'/%3E%3Cpath fill='rgba(0,58,112,0.05)' d='M1200 800c-174-187-302-325-513-384-139-39-231 29-349-56-97-71-145-141-338-160v600h1200z'/%3E%3Cpath fill='rgba(226,28,37,0.03)' d='M0 800c174-187 302-325 513-384 139-39 231 29-349-56 97-71 145-141 338-160v600H0z'/%3E%3C/g%3E%3C/svg%3E");
            background-attachment: fixed;
            background-size: cover;
        }
        
        [x-cloak] { display: none !important; }
        
        /* Animación personalizada para el badge destacado */
        @keyframes pulse-slow {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.85;
            }
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Estilos responsivos para dispositivos móviles */
        @media (max-width: 640px) {
            #radio-list {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.75rem !important;
            }
            
            #radio-list h2 {
                font-size: 1rem !important;
            }
            
            #radio-list .play-btn {
                padding: 0.5rem 0 !important;
                font-size: 0.875rem !important;
            }
        }
    </style>

    @livewireStyles
    
    <!-- RTCStream Player recursos - Solo CSS global -->
    <link rel="stylesheet" href="/css/rtc-player.css">
</head>
<body class="min-h-screen flex flex-col font-['Inter'] text-slate-900">
    <header class="bg-white shadow-md relative z-10">
        <div class="container max-w-7xl mx-auto py-4 px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <div class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-brand-blue to-brand-red">
                            Domiradios
                        </div>
                    </a>
                </div>
                
                <nav class="flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-brand-red font-medium transition-colors">Emisoras</a>
                    <a href="{{ route('ciudades.index') }}" class="text-gray-700 hover:text-brand-red font-medium transition-colors">Por Ciudad</a>
                    <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-brand-red font-medium transition-colors">Blog</a>
                    <a href="{{ route('favoritos') }}" class="text-gray-700 hover:text-brand-red font-medium transition-colors">Mis Favoritos</a>
                    <a href="{{ route('contacto') }}" class="text-white bg-gradient-to-r from-brand-blue to-brand-red hover:opacity-90 font-medium px-4 py-2 rounded-md transition-colors"><i class="fas fa-broadcast-tower mr-1"></i> Envía tu emisora</a>
                </nav>
            </div>
        </div>
    </header>

    
    {{-- Hero Section con buscador Livewire --}}
    @if(request()->route()->getName() == 'emisoras.index' || !request()->route()->getName())
        <div class="relative bg-gradient-to-br from-brand-blue to-brand-red text-white overflow-hidden py-16 md:py-24">
            <div class="absolute inset-0 opacity-10">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute bottom-0 left-0 w-full h-full">
                    <path fill="#ffffff" fill-opacity="1" d="M0,224L48,202.7C96,181,192,139,288,138.7C384,139,480,181,576,181.3C672,181,768,139,864,144C960,149,1056,203,1152,208C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
            <div class="container max-w-7xl mx-auto px-4 relative z-10">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl xl:text-6xl font-extrabold mb-4 drop-shadow-md">
                        Domiradios
                    </h1>
                    <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
    Directorio dominicano de emisoras de radio
</p>

<!-- Se eliminó el buscador del header para mantener solo el buscador Livewire del cuerpo de la página -->
                </div>
            </div>
        </div>
    @elseif(request()->route()->getName() == 'ciudades.index')
        <div class="relative bg-gradient-to-br from-brand-blue to-brand-red text-white overflow-hidden py-12 md:py-16">
            <div class="absolute inset-0 opacity-10">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute bottom-0 left-0 w-full h-full">
                    <path fill="#ffffff" fill-opacity="1" d="M0,224L48,202.7C96,181,192,139,288,138.7C384,139,480,181,576,181.3C672,181,768,139,864,144C960,149,1056,203,1152,208C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
            <div class="container max-w-7xl mx-auto px-4 relative z-10">
                <div class="text-center">
                    <h1 class="text-3xl md:text-4xl font-extrabold mb-2 drop-shadow-md">
                        Ciudades con Emisoras de Radio
                    </h1>
                    
                    <div class="mt-6 max-w-3xl mx-auto">
                        @livewire('search-emisoras', ['searchMode' => 'localFilter', 'genres' => $genres])
                    </div>
                </div>
            </div>
        </div>
    @endif

    
    <main class="flex-grow py-8">
        @yield('content')
    </main>

    <footer class="bg-brand-blue text-white mt-auto">
        <div class="container max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <h4 class="font-semibold mb-4 text-lg">Domiradios</h4>
                    <p class="text-gray-300">El directorio más completo de emisoras de radio dominicanas.</p>
                </div>
                {{-- Mantener el componente existente para los enlaces --}}
                <x-domiradios.footer-links />
            </div>
        </div>
        <div class="text-center py-4 bg-brand-red text-sm">
            <a href="https://rtcstreaming.com" target="_blank" rel="nofollow noopener noreferrer" class="text-white hover:underline">Directorio de JRMStream</a> | {{ date('Y') }} Domiradios
        </div>
    </footer>

    {{-- <script src="/css/tiny-slider.js"></script> --}} {{-- Archivo no existe - comentado --}}
    {{-- <script src="/css/glide.min.js"></script> --}} {{-- Archivo no existe - comentado --}}
    {{-- <script src="/js/index.js"></script> --}} {{-- Archivo no existe - comentado 2025-10-21 --}}

    @livewireScripts

</body>
</html>
