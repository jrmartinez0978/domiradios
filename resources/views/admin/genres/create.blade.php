@extends('layouts.admin')

@php $defaultType = request('type', 'genre'); @endphp

@section('title', $defaultType === 'city' ? 'Nueva Ciudad' : 'Nuevo Género')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.genres.index', ['type' => $defaultType]) }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $defaultType === 'city' ? 'Nueva Ciudad' : 'Nuevo Género' }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $defaultType === 'city' ? 'Crear una nueva ciudad' : 'Crear un nuevo género musical' }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.genres.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">{{ $defaultType === 'city' ? 'Información de la Ciudad' : 'Información del Género' }}</h3>

                <x-admin.form-input name="name" label="Nombre" required placeholder="{{ $defaultType === 'city' ? 'Nombre de la ciudad' : 'Nombre del género' }}" />
                <x-admin.form-input name="slug" label="Slug" placeholder="{{ $defaultType === 'city' ? 'nombre-de-la-ciudad' : 'nombre-del-genero' }}" />
                <x-admin.form-select name="type" label="Tipo" :options="['genre' => 'Género Musical', 'city' => 'Ciudad']" :value="$defaultType" required />
                <x-admin.form-file-upload name="img" label="Imagen" />
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> {{ $defaultType === 'city' ? 'Guardar Ciudad' : 'Guardar Género' }}
                </button>
            </div>
        </form>
    </div>
@endsection
