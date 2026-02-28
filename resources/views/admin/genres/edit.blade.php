@extends('layouts.admin')

@section('title', 'Editar Genero')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.genres.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Editar Genero</h2>
            <p class="text-dark-300 text-sm mt-1">{{ $genre->name }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.genres.update', $genre) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="glass-card p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-100 border-b border-glass-border pb-3">Informacion del Genero</h3>

                <x-admin.form-input name="name" label="Nombre" required :value="$genre->name" />
                <x-admin.form-input name="slug" label="Slug" :value="$genre->slug" />
                <x-admin.form-file-upload name="img" label="Imagen del genero" :currentImage="$genre->img" />
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Actualizar Genero
                </button>
            </div>
        </form>
    </div>
@endsection
