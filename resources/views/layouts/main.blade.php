<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Domiradios') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-['Inter'] bg-brand-gray text-slate-900 antialiased">

    <header class="relative bg-cover bg-center header-bg text-white overflow-hidden">
      <div class="backdrop-brightness-90">
        <div class="container max-w-6xl py-16 xl:py-24 text-center">
          <h1 class="text-5xl xl:text-7xl font-extrabold drop-shadow-lg">
            Domiradios
          </h1>
          <p class="mt-4 text-lg xl:text-2xl opacity-95">
            Directorio dominicano de emisoras
          </p>
          @livewire('search-emisoras') {{-- Mantiene tu componente existente --}}
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <footer class="bg-brand-blue text-white text-sm">
      <div class="container max-w-6xl py-12 grid gap-10 md:grid-cols-3">
        {{-- Bloque 1 --}}
        <div>
          <h4 class="font-semibold mb-3 text-lg">Domiradios</h4>
          <p>© {{ date('Y') }} Domiradios. Todos los derechos reservados.</p>
        </div>
        {{-- Bloques 2‑3 con links estáticos --}}
        <x-domiradios.footer-links />
      </div>
      <div class="text-center py-4 bg-brand-red text-xs">
        Hecho con ❤️ en RD
      </div>
    </footer>

    @livewireScripts
    @stack('scripts') {{-- Para scripts adicionales específicos de cada página --}}
</body>
</html>
