@extends('layouts.admin')

@section('title', 'Usuarios')

@section('page-header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Usuarios</h2>
            <p class="text-gray-500 text-sm mt-1">Gestiona los usuarios del sistema</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>
@endsection

@section('content')
    <x-admin.data-table :headers="['Nombre', 'Email', 'Creado', 'Acciones']">
        @forelse($users as $user)
            <tr class="hover:bg-primary-50 transition-colors">
                <td class="px-4 py-3 text-gray-800 font-medium">{{ $user->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                <td class="px-4 py-3 text-gray-500 text-sm">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-glass !px-3 !py-1.5 text-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Eliminar este usuario?')">
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
                <td colspan="4" class="px-4 py-8 text-center text-gray-400">No se encontraron usuarios.</td>
            </tr>
        @endforelse
    </x-admin.data-table>

    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
@endsection
