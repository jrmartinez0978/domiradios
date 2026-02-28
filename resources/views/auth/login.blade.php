<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Domiradios Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block">
                <h1 class="text-3xl font-extrabold">
                    <span class="text-gradient">Domiradios</span>
                </h1>
            </a>
            <p class="text-gray-400 mt-2">Panel de Administración</p>
        </div>

        <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Iniciar Sesión</h2>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                @foreach($errors->all() as $error)
                <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="glass-input w-full" required autofocus autocomplete="email">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600 mb-1">Contraseña</label>
                    <input type="password" id="password" name="password"
                           class="glass-input w-full" required autocomplete="current-password">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                           class="rounded bg-white border-surface-300 text-primary focus:ring-primary/50">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Recordarme</label>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> Entrar
                </button>
            </form>
        </div>

        <p class="text-center text-gray-400 text-sm mt-6">
            <a href="{{ url('/') }}" class="hover:text-primary transition-colors">&larr; Volver al sitio</a>
        </p>
    </div>
</body>
</html>
