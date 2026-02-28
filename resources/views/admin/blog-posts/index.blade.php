@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('page-header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Blog Posts</h2>
            <p class="text-gray-500 text-sm mt-1">Gestiona las publicaciones del blog</p>
        </div>
        <a href="{{ route('admin.blog-posts.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Post
        </a>
    </div>
@endsection

@section('content')
    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.blog-posts.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="w-44">
                <x-admin.form-select name="status" label="Estado" :value="request('status')" :options="['draft' => 'Borrador', 'published' => 'Publicado', 'scheduled' => 'Programado']" placeholder="Todos" />
            </div>
            <button type="submit" class="btn-glass inline-flex items-center gap-2">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </form>
    </div>

    <x-admin.data-table :headers="['Titulo', 'Categoria', 'Estado', 'Publicado', 'Acciones']">
        @forelse($posts as $post)
            <tr class="hover:bg-primary-50 transition-colors">
                <td class="px-4 py-3">
                    <span class="text-gray-800 font-medium">{{ $post->title }}</span>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $post->category ?? '-' }}</td>
                <td class="px-4 py-3">
                    @switch($post->status)
                        @case('published')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">Publicado</span>
                            @break
                        @case('scheduled')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">Programado</span>
                            @break
                        @default
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-surface-300">Borrador</span>
                    @endswitch
                </td>
                <td class="px-4 py-3 text-gray-500 text-sm">
                    {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : '-' }}
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn-glass !px-3 !py-1.5 text-xs">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" class="inline" onsubmit="return confirm('Eliminar este post?')">
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
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">No se encontraron posts.</td>
            </tr>
        @endforelse
    </x-admin.data-table>

    @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6">
            {{ $posts->withQueryString()->links() }}
        </div>
    @endif
@endsection
