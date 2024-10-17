<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MEXLLX8TZV"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MEXLLX8TZV');
</script>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5MBJX3P');</script>
    <!-- End Google Tag Manager -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $meta_title ?? 'Domiradios - Tu directorio de emisoras dominicanas' }}</title>

<meta name="description" content="{{ $meta_description ?? 'Descubre las mejores emisoras de radio de República Dominicana en nuestro directorio.' }}">
<meta name="keywords" content="{{ $meta_keywords ?? 'emisoras, radio, estaciones, géneros' }}">
<link rel="canonical" href="{{ $canonical_url ?? url()->current() }}">

<!-- Open Graph Meta Tags -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $meta_title ?? 'Domiradios - Escucha emisoras en vivo' }}">
<meta property="og:description" content="{{ $meta_description ?? 'Descubre emisoras de radio en vivo en nuestro directorio.' }}">
<meta property="og:url" content="{{ $canonical_url ?? url()->current() }}">
<meta property="og:image" content="{{ Storage::url($radio->img ?? 'default.png') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta_title ?? 'Domiradios - Escucha emisoras en vivo' }}">
<meta name="twitter:description" content="{{ $meta_description ?? 'Descubre emisoras de radio en vivo en nuestro directorio.' }}">
<meta name="twitter:image" content="{{ Storage::url($radio->img ?? 'default.png') }}">


    <!-- Styles -->
    @vite('resources/css/app.css') <!-- Incluir el CSS compilado de Tailwind -->
     <!-- Include Font Awesome -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>


    @livewireStyles
</head>
<body class="antialiased bg-gray-100">
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5MBJX3P"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Header -->
<header class="shadow-md font-sans tracking-wide relative z-50">
    <!-- Sección superior con dirección y contacto -->
    <section class="py-2 bg-blue-500 text-white text-right px-4 sm:px-6 lg:px-10">
        <p class="text-sm">
            <strong class="mx-3">Tu</strong>Directorio
            <strong class="mx-3">De Emisoras</strong>Dominicanas
        </p>
    </section>

    <!-- Contenedor principal del header -->
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-10 py-4 bg-white">
        <!-- Logo y Título adaptados -->
        <a href="{{ url('/') }}" class="flex items-center">
            <!-- Logo (opcional) -->
            <!-- <img src="https://tusitio.com/logo.png" alt="logo" class="w-36" /> -->
            <!-- Título -->
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 ml-2">
                <span class="text-blue-500 hover:text-red-500">Domiradios | </span>
                <span class="text-red-500 hover:text-blue-500">Emisoras</span>
                <span class="text-blue-500 hover:text-red-500">Dominicanas</span>
            </h1>
        </a>

        <!-- Botón para abrir el menú móvil -->
        <button id="menuToggle" class="lg:hidden focus:outline-none">
            <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3 5h14a1 1 0 010 2H3a1 1 0 110-2zm0 5h14a1 1 0 010 2H3a1 1 0 110-2zm0 5h14a1 1 0 010 2H3a1 1 0 110-2z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>

        <!-- Menú de navegación -->
        <nav id="navMenu" class="hidden lg:flex lg:items-center w-full lg:w-auto">
            <ul class="flex flex-col lg:flex-row lg:gap-x-5 mt-4 lg:mt-0">
                <!-- Enlaces de navegación -->
                <li class="border-b lg:border-0">
                    <a href="{{ url('/') }}" class="block px-4 py-2 lg:py-0 text-blue-500 hover:text-blue-700 font-bold">
                        Inicio
                    </a>
                </li>
                <li class="border-b lg:border-0">
                    <a href="{{ url('/ciudades') }}" class="block px-4 py-2 lg:py-0 text-gray-700 hover:text-blue-500 font-bold">
                        Ciudades
                    </a>
                </li>
                <li class="border-b lg:border-0">
                    <a href="{{ url('/favoritos') }}" class="block px-4 py-2 lg:py-0 text-gray-700 hover:text-blue-500 font-bold">
                        Favoritos
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!-- JavaScript para el menú móvil -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', function () {
            navMenu.classList.toggle('hidden');
        });
    });
</script>
<!-- Fin del header -->


    <main>
        @yield('content')
    </main>

    <footer class="border border-blue-300 bg-gray-200 text-gray-600 py-2">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <!-- Texto alineado a la izquierda -->
            <p>© 2024 Domiradios - Todos los derechos reservados</p>

            <!-- Navegación alineada a la derecha -->
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="/appdomiradios/terms-and-conditions" class="hover:text-gray-300 transition duration-300">Términos y Condiciones</a></li>
                    <li><a href="/appdomiradios/privacy-policy" class="hover:text-gray-300 transition duration-300">Política de Privacidad</a></li>
                </ul>
            </nav>
        </div>
    </footer>


    @livewireScripts
    @stack('modals')
    @vite('resources/js/app.js') <!-- Incluir el JavaScript compilado -->
</body>
</html>





