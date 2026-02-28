@extends('layouts.admin')

@section('title', 'Nuevo Usuario')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Nuevo Usuario</h2>
            <p class="text-dark-300 text-sm mt-1">Crear un nuevo usuario del sistema</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="glass-card p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-100 border-b border-glass-border pb-3">Informacion del Usuario</h3>

                <x-admin.form-input name="name" label="Nombre" required placeholder="Nombre completo" />
                <x-admin.form-input name="email" label="Email" type="email" required placeholder="usuario@ejemplo.com" />
                <x-admin.form-input name="password" label="Contrasena" type="password" required placeholder="Minimo 8 caracteres" />
                <x-admin.form-input name="password_confirmation" label="Confirmar Contrasena" type="password" required placeholder="Repetir contrasena" />
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
            </div>
        </form>
    </div>
@endsection
