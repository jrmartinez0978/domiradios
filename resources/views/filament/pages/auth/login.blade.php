<x-filament-panels::page.simple>
    <x-slot name="heading">
        <div class="fi-simple-header-ctn">
            <div class="fi-logo-wrapper">
                <img src="{{ asset('img/domiradios-logo-og.jpg') }}" alt="Domiradios" class="fi-logo-img">
            </div>
            <h2 class="fi-simple-heading-custom">
                Panel de Administración
            </h2>
            <p class="fi-simple-subheading">
                Gestiona tu directorio de radios dominicanas
            </p>
        </div>
    </x-slot>

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament::button
            type="submit"
            form="authenticate"
            class="w-full"
            size="lg"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Iniciar Sesión
        </x-filament::button>
    </x-filament-panels::form>

    <div class="fi-login-footer">
        <div class="fi-login-footer-content">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                © {{ date('Y') }} Domiradios. Todos los derechos reservados.
            </p>
            <div class="fi-login-footer-links">
                <a href="https://domiradios.com.do" target="_blank" class="text-sm text-primary-600 hover:text-primary-700 transition">
                    Visitar sitio web
                </a>
            </div>
        </div>
    </div>

    <style>
        .fi-simple-header-ctn {
            text-align: center;
            margin-bottom: 2rem;
        }

        .fi-logo-wrapper {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            margin-bottom: 1.5rem;
        }

        .fi-logo-img {
            max-height: 4rem;
            width: auto;
        }

        .fi-simple-heading-custom {
            color: #1a1a1a;
            font-weight: 800;
            font-size: 1.875rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .fi-simple-subheading {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        .fi-login-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f1f5f9;
        }

        .fi-login-footer-content {
            text-align: center;
        }

        .fi-login-footer-links {
            margin-top: 0.75rem;
        }

        /* Custom button styling */
        .fi-btn[type="submit"] {
            background: linear-gradient(135deg, #E21C25 0%, #c41820 100%);
            border: none;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 14px rgba(226, 28, 37, 0.4);
            transition: all 0.3s ease;
        }

        .fi-btn[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(226, 28, 37, 0.5);
            background: linear-gradient(135deg, #c41820 0%, #a01519 100%);
        }

        .fi-btn[type="submit"]:active {
            transform: translateY(0);
        }

        /* Form field improvements */
        .fi-input-wrp {
            margin-bottom: 1.25rem;
        }

        .fi-input {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .fi-input:focus {
            border-color: #E21C25;
            box-shadow: 0 0 0 3px rgba(226, 28, 37, 0.1);
            outline: none;
        }

        .fi-fo-field-wrp-label {
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        /* Checkbox styling */
        .fi-checkbox-input {
            border-radius: 0.375rem;
            border: 2px solid #e2e8f0;
        }

        .fi-checkbox-input:checked {
            background-color: #E21C25;
            border-color: #E21C25;
        }

        /* Error messages */
        .fi-fo-field-wrp-error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</x-filament-panels::page.simple>
