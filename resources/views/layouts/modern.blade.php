<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Domiradios | Directorio de Emisoras Dominicanas</title>

  <!-- Tailwind CSS via CDN (v3.x) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'brand-red':  '#E21C25',
            'brand-blue': '#003A70',
            'brand-gray': '#F5F7FA',
          },
          container: {
            center: true,
            padding: '1rem',
          }
        }
      }
    }
  </script>

  <style>
    /* Imagen de fondo para Desktop (1920×1080). Guarda la imagen #1 como /img/header-bg-desktop.png */
    .header-bg {
      background-image: url('/img/header-bg-desktop.png');
    }
  </style>
  
  <!-- CSRF Token para formularios -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Livewire Styles -->
  @livewireStyles
</head>
<body class="font-['Inter'] text-slate-900 bg-brand-gray">
  <!-- ======================= HEADER ======================= -->
  <header class="relative header-bg bg-cover bg-center text-white overflow-hidden">
    <div class="backdrop-brightness-90">
      <div class="container max-w-6xl py-16 xl:py-24 flex flex-col items-center">
        <h1 class="text-5xl xl:text-7xl font-extrabold drop-shadow-lg">Domiradios</h1>
        <p class="mt-4 text-lg xl:text-2xl font-medium opacity-95 tracking-wide">
          Directorio dominicano de emisoras
        </p>
        <!-- Buscador principal -->
        <div class="w-full max-w-2xl mt-10">
          <form action="{{ route('buscar') }}" method="GET">
            <label class="sr-only" for="search">Buscar emisoras</label>
            <div class="flex bg-white rounded-full overflow-hidden shadow-xl">
              <input id="search" name="q" type="text" placeholder="Buscar emisoras…" class="flex-1 px-6 py-3 xl:py-4 text-slate-700 focus:outline-none" />
              <button class="px-6 py-3 xl:py-4 bg-brand-blue hover:bg-brand-red transition-colors focus:outline-none">
                🔍
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </header>

  <!-- ======================= CONTENIDO PRINCIPAL ======================= -->
  @yield('content')

  <!-- ======================= FOOTER ======================= -->
  <footer class="bg-brand-blue text-white text-sm">
    <div class="container max-w-6xl py-12 grid gap-10 md:grid-cols-3">
      <div>
        <h4 class="font-semibold mb-3 text-lg">Domiradios</h4>
        <p>© {{ date('Y') }} Domiradios. Todos los derechos reservados.</p>
      </div>
      <nav>
        <h4 class="font-semibold mb-3 text-lg">Secciones</h4>
        <ul class="space-y-1">
          <li><a class="hover:text-brand-red" href="{{ url('/') }}">Inicio</a></li>
          <li><a class="hover:text-brand-red" href="{{ url('/emisoras') }}">Emisoras</a></li>
          <li><a class="hover:text-brand-red" href="{{ url('/generos') }}">Géneros</a></li>
          <li><a class="hover:text-brand-red" href="{{ url('/ciudades') }}">Ciudades</a></li>
        </ul>
      </nav>
      <nav>
        <h4 class="font-semibold mb-3 text-lg">Legal</h4>
        <ul class="space-y-1">
          <li><a class="hover:text-brand-red" href="/appdomiradios/terms-and-conditions">Términos y condiciones</a></li>
          <li><a class="hover:text-brand-red" href="/appdomiradios/privacy-policy">Política de privacidad</a></li>
          <li><a class="hover:text-brand-red" href="#contacto">Contacto</a></li>
        </ul>
      </nav>
    </div>
    <div class="text-center py-4 bg-brand-red text-xs">
      Hecho con ❤️ en RD
    </div>
  </footer>
  
  <!-- Livewire Scripts -->
  @livewireScripts
  
  <!-- Laravel Vite -->
  @vite('resources/js/app.js')
  
  <!-- Scripts adicionales -->
  @stack('scripts')
</body>
</html>
