@extends('layouts.admin')

@section('title', 'Emisoras')

@section('page-header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Emisoras</h2>
            <p class="text-gray-500 text-sm mt-1">Gestiona las estaciones de radio</p>
        </div>
        <a href="{{ route('admin.radios.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Emisora
        </a>
    </div>
@endsection

@section('content')
    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.radios.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <x-admin.form-input name="search" label="Buscar" :value="request('search')" placeholder="Nombre, frecuencia..." />
            </div>
            <div class="w-40">
                <x-admin.form-select name="status" label="Estado" :value="request('status')" :options="['active' => 'Activa', 'inactive' => 'Inactiva']" placeholder="Todos" />
            </div>
            <div class="w-40">
                <x-admin.form-select name="featured" label="Destacada" :value="request('featured')" :options="['yes' => 'Si', 'no' => 'No']" placeholder="Todas" />
            </div>
            <button type="submit" class="btn-glass inline-flex items-center gap-2">
                <i class="fas fa-search"></i> Filtrar
            </button>
        </form>
    </div>

    {{-- Bulk Actions --}}
    <form method="POST" action="{{ route('admin.radios.bulk-action') }}" id="bulkForm" class="mb-4">
        @csrf
        <div class="flex items-center gap-3">
            <select name="action" class="glass-input !py-2 text-sm w-44">
                <option value="">Acciones masivas</option>
                <option value="activate">Activar</option>
                <option value="deactivate">Desactivar</option>
                <option value="feature">Destacar</option>
                <option value="unfeature">Quitar destacado</option>
                <option value="delete">Eliminar</option>
            </select>
            <button type="submit" class="btn-glass text-sm" onclick="return confirm('Seguro que deseas realizar esta accion?')">
                Aplicar
            </button>
        </div>

    {{-- Data Table --}}
    <x-admin.data-table :headers="['Imagen', 'Nombre', 'Frecuencia', 'Source', 'Estado', 'Destacada', 'Rating', 'Acciones']" :checkboxes="true">
        @forelse($radios as $radio)
            <tr class="hover:bg-primary-50 transition-colors">
                <td class="px-4 py-3">
                    <input type="checkbox" name="ids[]" value="{{ $radio->id }}" class="row-checkbox w-4 h-4 rounded bg-white border-surface-300 text-primary focus:ring-primary/50 focus:ring-offset-0">
                </td>
                <td class="px-4 py-3">
                    <img src="{{ $radio->optimized_logo_url }}" alt="{{ $radio->name }}" class="w-10 h-10 rounded-lg object-cover border border-surface-300">
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.radios.show', $radio) }}" class="text-gray-800 hover:text-primary transition-colors font-medium">
                        {{ $radio->name }}
                    </a>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $radio->bitrate ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $radio->source_radio ?? '-' }}</td>
                <td class="px-4 py-3">
                    @if($radio->isActive)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">Activa</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-surface-300">Inactiva</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($radio->isFeatured)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">Destacada</span>
                    @else
                        <span class="text-gray-400 text-xs">-</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= $radio->rating ? 'text-amber-500' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.radios.edit', $radio) }}" class="btn-glass !px-3 !py-1.5 text-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.radios.destroy', $radio) }}" class="inline" onsubmit="return confirm('Eliminar esta emisora?')">
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
                <td colspan="9" class="px-4 py-8 text-center text-gray-400">No se encontraron emisoras.</td>
            </tr>
        @endforelse
    </x-admin.data-table>
    </form>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $radios->withQueryString()->links() }}
    </div>
@endsection
