@extends('layouts.admin')

@section('title', isset($type) && $type === 'city' ? 'Ciudades' : 'Géneros')

@section('page-header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ isset($type) && $type === 'city' ? 'Ciudades' : 'Géneros Musicales' }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ isset($type) && $type === 'city' ? 'Gestiona las ciudades' : 'Gestiona los géneros musicales' }}</p>
        </div>
        <a href="{{ route('admin.genres.create', ['type' => $type ?? 'genre']) }}" class="btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> {{ isset($type) && $type === 'city' ? 'Nueva Ciudad' : 'Nuevo Género' }}
        </a>
    </div>
@endsection

@section('content')
    <x-admin.data-table :headers="['Imagen', 'Nombre', 'Slug', 'Tipo', 'Radios', 'Acciones']">
        @forelse($genres as $genre)
            <tr class="hover:bg-primary-50 transition-colors">
                <td class="px-4 py-3">
                    @if($genre->img)
                        <img src="{{ Storage::url($genre->img) }}" alt="{{ $genre->name }}" class="w-10 h-10 rounded-lg object-cover border border-surface-300">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-surface-100 border border-surface-300 flex items-center justify-center">
                            <i class="fas {{ $genre->type === 'city' ? 'fa-city' : 'fa-music' }} text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-800 font-medium">{{ $genre->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $genre->slug }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $genre->type === 'city' ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-purple-50 text-purple-600 border border-purple-200' }}">
                        {{ $genre->type === 'city' ? 'Ciudad' : 'Género' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200">
                        {{ $genre->radios_count ?? $genre->radios()->count() }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.genres.edit', $genre) }}" class="btn-glass !px-3 !py-1.5 text-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.genres.destroy', $genre) }}" class="inline" onsubmit="return confirm('Eliminar este genero?')">
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
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">No se encontraron {{ isset($type) && $type === 'city' ? 'ciudades' : 'géneros' }}.</td>
            </tr>
        @endforelse
    </x-admin.data-table>

    @if($genres instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6">
            {{ $genres->links() }}
        </div>
    @endif
@endsection
