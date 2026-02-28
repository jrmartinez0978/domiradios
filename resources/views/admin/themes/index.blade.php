@extends('layouts.admin')

@section('title', 'Temas')

@section('page-header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Temas</h2>
            <p class="text-dark-300 text-sm mt-1">Gestiona los temas visuales del frontend</p>
        </div>
        <a href="{{ route('admin.themes.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Tema
        </a>
    </div>
@endsection

@section('content')
    <x-admin.data-table :headers="['Nombre', 'Activo', 'Acciones']">
        @forelse($themes as $theme)
            <tr class="hover:bg-glass-white-10 transition-colors">
                <td class="px-4 py-3 text-gray-100 font-medium">{{ $theme->name }}</td>
                <td class="px-4 py-3">
                    @if($theme->is_active ?? $theme->isActive ?? false)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-accent-green/20 text-green-300 border border-accent-green/30">
                            <i class="fas fa-check text-[10px]"></i> Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-dark-600 text-dark-300 border border-glass-border">
                            Inactivo
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.themes.edit', $theme) }}" class="btn-glass !px-3 !py-1.5 text-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.themes.destroy', $theme) }}" class="inline" onsubmit="return confirm('Eliminar este tema?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-glass !px-3 !py-1.5 text-xs text-accent-red border-accent-red/30 hover:bg-accent-red/20">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="px-4 py-8 text-center text-dark-400">No se encontraron temas.</td>
            </tr>
        @endforelse
    </x-admin.data-table>
@endsection
