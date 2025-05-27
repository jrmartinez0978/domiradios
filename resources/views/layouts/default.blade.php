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
<body class="antialiased bg-gray-50">
    <!-- Añadimos estilos para las animaciones -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out forwards;
        }
        
        .animate-slideIn {
            animation: slideIn 0.3s ease-out forwards;
        }
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5MBJX3P"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Header -->
    {{-- <header class="relative bg-cover bg-center header-bg text-white overflow-hidden">
      <div class="backdrop-brightness-90">
        <div class="container max-w-6xl py-16 xl:py-24 text-center">
          <h1 class="text-5xl xl:text-7xl font-extrabold drop-shadow-lg">
            Domiradios
          </h1>
          <p class="mt-4 text-lg xl:text-2xl opacity-95">
            Directorio dominicano de emisoras
          </p>
          {{-- @livewire('search-emisoras') --}} {{-- Mantenido tu componente existente, temporalmente comentado --}}
        </div>
      </div>
    </header> --}}

        menuToggle.addEventListener('click', function () {
            // Toggle para la clase hidden
            navMenu.classList.toggle('hidden');
            
            // Animación del botón
            this.classList.toggle('bg-emerald-100');
            
            // Si el menú está visible, añadir animación de entrada
            if (!navMenu.classList.contains('hidden')) {
                navMenu.classList.add('animate-fadeIn');
                // Animación para cada elemento del menú
                const menuItems = navMenu.querySelectorAll('li');
                menuItems.forEach((item, index) => {
                    item.style.animationDelay = `${index * 0.05}s`;
                    item.classList.add('animate-slideIn');
                });
            } else {
                navMenu.classList.remove('animate-fadeIn');
                const menuItems = navMenu.querySelectorAll('li');
                menuItems.forEach(item => {
                    item.classList.remove('animate-slideIn');
                });
            }
        });
        
        // Cerrar el menú al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!navMenu.classList.contains('hidden') && 
                !navMenu.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                navMenu.classList.add('hidden');
                menuToggle.classList.remove('bg-emerald-100');
            }
        });
    });
</script>
<!-- Fin del header -->


    <main>
        @yield('content')
    </main>

    {{-- <footer class="bg-brand-blue text-white text-sm">
      <div class="container max-w-6xl py-12 grid gap-10 md:grid-cols-3">
        {{-- bloque 1 --}}
        <div>
          <h4 class="font-semibold mb-3 text-lg">Domiradios</h4>
          <p>© {{ date('Y') }} Domiradios. Todos los derechos reservados.</p>
        </div>
        {{-- bloques 2‑3 con links estáticos --}}
        <x-domiradios.footer-links />
      </div>
      <div class="text-center py-4 bg-brand-red text-xs">
        Hecho con ❤️ en RD
      </div>
    </footer> --}}


    @livewireScripts
    @stack('modals')
    @vite('resources/js/app.js') <!-- Incluir el JavaScript compilado -->
</body>
</html>





