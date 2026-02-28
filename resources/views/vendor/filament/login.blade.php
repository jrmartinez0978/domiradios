@php
    $brandBlue = 'from-brand-blue';
    $brandRed = 'to-brand-red';
@endphp
<x-filament::layouts.app>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br {{ $brandBlue }} {{ $brandRed }}">
        <div class="bg-white/90 p-8 rounded-xl shadow-lg w-full max-w-md">
            <div class="mb-6 text-center">
                <img src="{{ asset('img/domiradios-logo.png') }}" alt="Domiradios" class="mx-auto h-16 mb-2">
                <h1 class="text-2xl font-bold text-brand-blue">Panel Domiradios</h1>
                <p class="text-sm text-gray-500 mt-2">Acceso restringido para administradores</p>
            </div>
            {{ $this->form }}
            <div class="mt-4 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Domiradios
            </div>
        </div>
    </div>
</x-filament::layouts.app>
