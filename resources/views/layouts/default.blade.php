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

    <header class="bg-blue-200 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-stone-200 rounded-full px-6 py-2 inline-block border border-gray-600">
                <h1 class="text-3xl font-bold text-gray-900">
                    <a href="{{ url('/') }}" class="hover:text-blue-500">
                        <span class="text-blue-500 hover:text-red-500">Domiradios | </span>
                        <span class="text-red-500 hover:text-blue-500">Emisoras</span>
                        <span class="text-blue-500 hover:text-red-500">Dominicanas</span>
                    </a>
                </h1>
            </div>
        </div>
    </header>


    <nav class="border border-blue-300">
        <ul class="flex justify-center bg-gray-300 text-blue-400 py-2">
            <li><a href="/" class="px-6 hover:text-red-300 transition duration-300">INICIO</a></li>
            <li><a href="/ciudades" class="px-6 hover:text-red-300 transition duration-300">CIUDADES</a></li>
            <li><a href="/favoritos" class="px-6 hover:text-red-300 transition duration-300">FAVORITOS</a></li>
        </ul>
    </nav>

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
    @vite('resources/js/app.js') <!-- Incluir el JavaScript compilado -->
</body>
</html>





