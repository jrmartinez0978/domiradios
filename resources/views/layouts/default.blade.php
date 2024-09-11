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

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="@yield('og_title', 'Directorio de Radios en Vivo')" />
    <meta property="og:description" content="@yield('og_description', 'Encuentra emisoras de radio dominicanas en vivo.')" />
    <meta property="og:image" content="@yield('og_image', asset('images/default-og-image.jpg'))" />
    <meta property="og:url" content="@yield('og_url', url()->current())" />
    <meta property="og:type" content="website" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('twitter_title', 'Directorio de Radios')" />
    <meta name="twitter:description" content="@yield('twitter_description', 'Encuentra emisoras de radio dominicanas en vivo.')" />
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/default-twitter-image.jpg'))" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
     <!-- Include Font Awesome -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


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


    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="max-w-7xl mx-auto px-4">
            <p class="text-center">© 2024 Domiradios - Todos los derechos reservados</p>
        </div>
    </footer>

    @livewireScripts

</body>
</html>





