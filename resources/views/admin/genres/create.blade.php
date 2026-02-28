@extends('layouts.admin')

@section('title', 'Nuevo Genero')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.genres.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Nuevo Genero</h2>
            <p class="text-dark-300 text-sm mt-1">Crear un nuevo genero musical</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.genres.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="glass-card p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-100 border-b border-glass-border pb-3">Informacion del Genero</h3>

                <x-admin.form-input name="name" label="Nombre" required placeholder="Nombre del genero" />
                <x-admin.form-input name="slug" label="Slug" placeholder="nombre-del-genero" />
                <x-admin.form-file-upload name="img" label="Imagen del genero" />
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Genero
                </button>
            </div>
        </form>
    </div>
@endsection
