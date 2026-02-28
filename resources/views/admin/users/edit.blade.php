@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Editar Usuario</h2>
            <p class="text-dark-300 text-sm mt-1">{{ $user->name }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="glass-card p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-100 border-b border-glass-border pb-3">Informacion del Usuario</h3>

                <x-admin.form-input name="name" label="Nombre" required :value="$user->name" />
                <x-admin.form-input name="email" label="Email" type="email" required :value="$user->email" />
                <x-admin.form-input name="password" label="Contrasena" type="password" placeholder="Dejar en blanco para mantener la actual" />
                <x-admin.form-input name="password_confirmation" label="Confirmar Contrasena" type="password" placeholder="Repetir nueva contrasena" />

                <p class="text-xs text-dark-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    La contrasena es opcional. Solo completa si deseas cambiarla.
                </p>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
@endsection
