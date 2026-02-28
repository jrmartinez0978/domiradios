@extends('layouts.admin')

@section('title', 'Temas')

@section('page-header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Temas</h2>
            <p class="text-gray-500 text-sm mt-1">Gestiona los temas visuales del frontend</p>
        </div>
        <a href="{{ route('admin.themes.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Tema
        </a>
    </div>
@endsection

@section('content')
    <x-admin.data-table :headers="['Nombre', 'Activo', 'Acciones']">
        @forelse($themes as $theme)
            <tr class="hover:bg-primary-50 transition-colors">
                <td class="px-4 py-3 text-gray-800 font-medium">{{ $theme->name }}</td>
                <td class="px-4 py-3">
                    @if($theme->is_active ?? $theme->isActive ?? false)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                            <i class="fas fa-check text-[10px]"></i> Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-surface-300">
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
                            <button type="submit" class="btn-glass !px-3 !py-1.5 text-xs text-red-600 border-red-200 hover:bg-red-50">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="px-4 py-8 text-center text-gray-400">No se encontraron temas.</td>
            </tr>
        @endforelse
    </x-admin.data-table>
@endsection
