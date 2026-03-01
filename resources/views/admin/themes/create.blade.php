@extends('layouts.admin')

@section('title', 'Nuevo Tema')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.themes.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Nuevo Tema</h2>
            <p class="text-gray-500 text-sm mt-1">Crear un nuevo tema visual</p>
        </div>
    </div>
@endsection

@php
    $orientationOptions = [
        0 => 'Arriba a Abajo (0°)',
        45 => 'Esquina Superior-Izq a Inferior-Der (45°)',
        90 => 'Izquierda a Derecha (90°)',
        135 => 'Esquina Inferior-Izq a Superior-Der (135°)',
        180 => 'Abajo a Arriba (180°)',
        225 => 'Esquina Inferior-Der a Superior-Izq (225°)',
        270 => 'Derecha a Izquierda (270°)',
        315 => 'Esquina Superior-Der a Inferior-Izq (315°)',
    ];
@endphp

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.themes.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Informacion del Tema</h3>

                <x-admin.form-input name="name" label="Nombre" required placeholder="Nombre del tema" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-toggle name="is_active" label="Activo" :checked="true" />
                    <x-admin.form-toggle name="is_single_theme" label="Tema Unico" />
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Colores del Gradiente</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="grad_start_color" class="block text-sm font-medium text-gray-600 mb-1.5">Color Inicio</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="grad_start_color" id="grad_start_color" value="{{ old('grad_start_color', '#667eea') }}" class="h-10 w-14 rounded-lg border border-surface-300 cursor-pointer">
                            <input type="text" id="grad_start_color_text" value="{{ old('grad_start_color', '#667eea') }}" class="glass-input flex-1" maxlength="7" oninput="document.getElementById('grad_start_color').value = this.value" onchange="document.getElementById('grad_start_color').value = this.value">
                        </div>
                        @error('grad_start_color')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="grad_end_color" class="block text-sm font-medium text-gray-600 mb-1.5">Color Fin</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="grad_end_color" id="grad_end_color" value="{{ old('grad_end_color', '#764ba2') }}" class="h-10 w-14 rounded-lg border border-surface-300 cursor-pointer">
                            <input type="text" id="grad_end_color_text" value="{{ old('grad_end_color', '#764ba2') }}" class="glass-input flex-1" maxlength="7" oninput="document.getElementById('grad_end_color').value = this.value" onchange="document.getElementById('grad_end_color').value = this.value">
                        </div>
                        @error('grad_end_color')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <x-admin.form-select name="grad_orientation" label="Orientacion del Gradiente" :options="$orientationOptions" :value="old('grad_orientation', 315)" />

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Vista Previa</label>
                    <div id="gradient-preview" class="h-20 rounded-xl border border-surface-300" style="background: linear-gradient(315deg, {{ old('grad_start_color', '#667eea') }}, {{ old('grad_end_color', '#764ba2') }})"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Imagen de Fondo</h3>
                <x-admin.form-file-upload name="img" label="Imagen (720x1280 recomendado)" />
            </div>

            <div>
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Tema
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startColor = document.getElementById('grad_start_color');
    const endColor = document.getElementById('grad_end_color');
    const startText = document.getElementById('grad_start_color_text');
    const endText = document.getElementById('grad_end_color_text');
    const orientation = document.getElementById('grad_orientation');
    const preview = document.getElementById('gradient-preview');

    function updatePreview() {
        const deg = orientation.value || 315;
        preview.style.background = `linear-gradient(${deg}deg, ${startColor.value}, ${endColor.value})`;
    }

    startColor.addEventListener('input', function() { startText.value = this.value; updatePreview(); });
    endColor.addEventListener('input', function() { endText.value = this.value; updatePreview(); });
    startText.addEventListener('input', function() { if (/^#[0-9a-fA-F]{6}$/.test(this.value)) { startColor.value = this.value; updatePreview(); } });
    endText.addEventListener('input', function() { if (/^#[0-9a-fA-F]{6}$/.test(this.value)) { endColor.value = this.value; updatePreview(); } });
    orientation.addEventListener('change', updatePreview);
});
</script>
@endpush
