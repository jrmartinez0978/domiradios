@extends('layouts.admin')

@section('title', 'Editar Tema')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.themes.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Editar Tema</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $theme->name }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.themes.update', $theme) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Informacion del Tema</h3>

                <x-admin.form-input name="name" label="Nombre" required :value="$theme->name" />
                <x-admin.form-toggle name="is_active" label="Activo" :checked="$theme->is_active ?? $theme->isActive ?? false" />
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Actualizar Tema
                </button>
            </div>
        </form>
    </div>
@endsection
