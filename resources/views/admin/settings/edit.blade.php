@extends('layouts.admin')

@section('title', 'Ajustes')

@section('page-header')
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Ajustes</h2>
        <p class="text-gray-500 text-sm mt-1">Configuracion general del sistema</p>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Informacion de la App --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Informacion de la App</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="app_name" label="Nombre de la App" :value="$setting->app_name" />
                    <x-admin.form-input name="app_email" label="Correo Electronico" type="email" :value="$setting->app_email" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="app_phone" label="Telefono" :value="$setting->app_phone" />
                    <x-admin.form-input name="app_copyright" label="Copyright" :value="$setting->app_copyright" />
                </div>
            </div>

            {{-- Redes y Web --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Redes Sociales y Web</h3>

                <x-admin.form-input name="app_website" label="Sitio Web" type="url" :value="$setting->app_website" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="app_facebook" label="Facebook" type="url" :value="$setting->app_facebook" />
                    <x-admin.form-input name="app_twitter" label="Twitter" type="url" :value="$setting->app_twitter" />
                </div>
            </div>

            {{-- Terminos y Privacidad --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Legal</h3>

                <x-admin.form-textarea name="app_term_of_use" label="Terminos de Uso" :value="$setting->app_term_of_use" rows="8" />
                <x-admin.form-textarea name="app_privacy_policy" label="Politica de Privacidad" :value="$setting->app_privacy_policy" rows="8" />
            </div>

            <div>
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Ajustes
                </button>
            </div>
        </form>
    </div>
@endsection
