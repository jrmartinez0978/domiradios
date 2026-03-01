@extends('layouts.admin')

@section('title', 'Configuracion')

@section('page-header')
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Configuracion</h2>
        <p class="text-gray-500 text-sm mt-1">Ajustes de la aplicacion movil</p>
    </div>
@endsection

@php
    $uiLayoutOptions = [
        1 => 'Cuadricula Plana',
        2 => 'Lista Plana',
        3 => 'Cuadricula con Tarjetas',
        4 => 'Lista con Tarjetas',
        5 => 'Cuadricula Horizontal',
        6 => 'Lista Horizontal',
    ];
    $playerStyleOptions = [
        1 => 'Estilo 1',
        2 => 'Estilo 2',
        3 => 'Estilo 3',
        4 => 'Estilo 4',
        5 => 'Estilo 5',
        6 => 'Estilo 6',
    ];
    $appTypeOptions = [
        1 => 'Multiples Emisoras',
        2 => 'Emisora Unica',
    ];
@endphp

@section('content')
    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.configs.update') }}">
            @csrf
            @method('PUT')

            {{-- App Type --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Tipo de App</h3>

                <x-admin.form-select name="app_type" label="Tipo de Aplicacion" :options="$appTypeOptions" :value="$config->app_type" required />

                <x-admin.form-toggle name="is_full_bg" label="Fondo completo" :checked="(bool) $config->is_full_bg" />
            </div>

            {{-- UI Layouts --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Estilos de UI</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-select name="ui_top_chart" label="Top Chart" :options="$uiLayoutOptions" :value="$config->ui_top_chart" required />
                    <x-admin.form-select name="ui_genre" label="Generos" :options="$uiLayoutOptions" :value="$config->ui_genre" required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-select name="ui_favorite" label="Favoritos" :options="$uiLayoutOptions" :value="$config->ui_favorite" required />
                    <x-admin.form-select name="ui_themes" label="Temas" :options="$uiLayoutOptions" :value="$config->ui_themes" required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-select name="ui_detail_genre" label="Detalle Genero" :options="$uiLayoutOptions" :value="$config->ui_detail_genre" required />
                    <x-admin.form-select name="ui_search" label="Busqueda" :options="$uiLayoutOptions" :value="$config->ui_search" required />
                </div>
            </div>

            {{-- Player Style --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Estilo del Reproductor</h3>

                <x-admin.form-select name="ui_player" label="Estilo del Reproductor" :options="$playerStyleOptions" :value="$config->ui_player" required />
            </div>

            <div>
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Configuracion
                </button>
            </div>
        </form>
    </div>
@endsection
