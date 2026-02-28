@extends('layouts.admin')

@section('title', 'Generos')

@section('page-header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Generos</h2>
            <p class="text-dark-300 text-sm mt-1">Gestiona los generos musicales</p>
        </div>
        <a href="{{ route('admin.genres.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Genero
        </a>
    </div>
@endsection

@section('content')
    <x-admin.data-table :headers="['Imagen', 'Nombre', 'Slug', 'Radios', 'Acciones']">
        @forelse($genres as $genre)
            <tr class="hover:bg-glass-white-10 transition-colors">
                <td class="px-4 py-3">
                    @if($genre->img)
                        <img src="{{ Storage::url($genre->img) }}" alt="{{ $genre->name }}" class="w-10 h-10 rounded-lg object-cover border border-glass-border">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-dark-700 border border-glass-border flex items-center justify-center">
                            <i class="fas fa-music text-dark-400"></i>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-100 font-medium">{{ $genre->name }}</td>
                <td class="px-4 py-3 text-dark-300">{{ $genre->slug }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-accent-blue/20 text-blue-300 border border-accent-blue/30">
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
                            <button type="submit" class="btn-glass !px-3 !py-1.5 text-xs text-accent-red border-accent-red/30 hover:bg-accent-red/20">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-dark-400">No se encontraron generos.</td>
            </tr>
        @endforelse
    </x-admin.data-table>

    @if($genres instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6">
            {{ $genres->links() }}
        </div>
    @endif
@endsection
