@extends('layouts.admin')

@section('title', $genre->type === 'city' ? 'Editar Ciudad' : 'Editar Género')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.genres.index', ['type' => $genre->type]) }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $genre->type === 'city' ? 'Editar Ciudad' : 'Editar Género' }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $genre->name }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.genres.update', $genre) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">{{ $genre->type === 'city' ? 'Información de la Ciudad' : 'Información del Género' }}</h3>

                <x-admin.form-input name="name" label="Nombre" required :value="$genre->name" />
                <x-admin.form-input name="slug" label="Slug" :value="$genre->slug" />
                <x-admin.form-select name="type" label="Tipo" :options="['genre' => 'Género Musical', 'city' => 'Ciudad']" :value="$genre->type" required />
                <x-admin.form-file-upload name="img" label="Imagen" :currentImage="$genre->img" />
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> {{ $genre->type === 'city' ? 'Actualizar Ciudad' : 'Actualizar Género' }}
                </button>
            </div>
        </form>
    </div>
@endsection
