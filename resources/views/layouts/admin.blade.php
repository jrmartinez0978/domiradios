<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Admin Panel') - Domiradios</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Chart.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Critical inline styles --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @livewireStyles
    @stack('styles')
</head>
<body class="bg-surface-100 text-gray-800 font-sans antialiased min-h-screen" x-data="{ sidebarOpen: true }">

    {{-- Top Bar --}}
    <header class="fixed top-0 inset-x-0 z-40 h-16 bg-primary shadow-sm flex items-center px-4 lg:px-6">
        {{-- Hamburger Toggle --}}
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-colors mr-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Title --}}
        <h1 class="text-lg font-bold text-white mr-6">Admin Panel</h1>

        {{-- Search --}}
        <div class="hidden sm:block flex-1 max-w-md">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/50 text-sm"></i>
                <input type="text" placeholder="Buscar..." class="w-full bg-white/10 border border-white/20 rounded-xl py-2 pl-10 pr-4 text-sm text-white placeholder-white/50 focus:bg-white/20 focus:border-white/40 transition-all">
            </div>
        </div>

        {{-- Right Side --}}
        <div class="ml-auto flex items-center gap-4">
            <span class="hidden md:inline text-sm text-white/80">{{ auth()->user()->name ?? 'Admin' }}</span>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-colors text-sm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="hidden sm:inline">Salir</span>
                </button>
            </form>
        </div>
    </header>

    {{-- Sidebar --}}
    <x-admin.sidebar />

    {{-- Main Content --}}
    <main
        class="pt-16 transition-all duration-300"
        :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'"
    >
        <div class="p-4 lg:p-6">
            {{-- Flash Messages --}}
            @if(session('success'))
                <x-admin.notification type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-admin.notification type="error" :message="session('error')" />
            @endif

            {{-- Page Header --}}
            @hasSection('page-header')
                <div class="mb-6">
                    @yield('page-header')
                </div>
            @endif

            {{-- Content --}}
            @yield('content')
        </div>
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>
