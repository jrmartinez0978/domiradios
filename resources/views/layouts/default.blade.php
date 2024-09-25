<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Domiradios - Tu directorio de emisoras dominicanas')</title>

    <!-- SEO Meta Tags -->
<meta name="description" content="@yield('meta_description', 'Directorio de emisoras de radio en vivo. Encuentra y escucha emisoras de radio dominicanas.')" />
<meta name="keywords" content="@yield('meta_keywords', 'radio, emisoras, dominicanas, en vivo, streaming')" />
<link rel="canonical" href="@yield('canonical_url', url()->current())">


<!-- Open Graph Meta Tags -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $meta_title }}">
<meta property="og:description" content="{{ $meta_description }}">
<meta property="og:url" content="{{ $canonical_url }}">
<meta property="og:image" content="{{ Storage::url($radio->img) }}">
<meta property="og:site_name" content="{{ config('app.name') }}">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta_title }}">
<meta name="twitter:description" content="{{ $meta_description }}">
<meta name="twitter:image" content="{{ Storage::url($radio->img) }}">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
     <!-- Include Font Awesome -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>


    @livewireStyles
</head>
<body class="antialiased bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <a href="{{ url('/') }}" class="hover:text-blue-500">
                    @yield('header', 'Domiradios | Emisoras Dominicanas')
                </a>
            </h1>
        </div>
    </header>
<nav>
    <ul class="flex justify-center bg-gray-800 text-white py-4">
        <li><a href="/" class="px-6 hover:text-gray-300 transition duration-300">Inicio</a></li>
        <li><a href="/ciudades" class="px-6 hover:text-gray-300 transition duration-300">Ciudades</a></li>
        <li><a href="/favoritos" class="px-6 hover:text-gray-300 transition duration-300">Favoritos</a></li>
    </ul>
</nav>
    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-4">
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

</body>
</html>





