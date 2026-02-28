@extends('layouts.admin')

@section('title', $radio->name)

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.radios.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-800">{{ $radio->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">Detalles de la emisora</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.radios.edit', $radio) }}" class="btn-glass inline-flex items-center gap-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <form method="POST" action="{{ route('admin.radios.destroy', $radio) }}" onsubmit="return confirm('Eliminar esta emisora?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-glass text-red-600 border-red-200 hover:bg-red-50 inline-flex items-center gap-2">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3 mb-5">Informacion General</h3>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Nombre</dt>
                        <dd class="mt-1 text-gray-800">{{ $radio->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Slug</dt>
                        <dd class="mt-1 text-gray-500">{{ $radio->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Frecuencia / Bitrate</dt>
                        <dd class="mt-1 text-gray-500">{{ $radio->bitrate ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Fuente</dt>
                        <dd class="mt-1 text-gray-500">{{ $radio->source_radio ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Tipo Audio</dt>
                        <dd class="mt-1 text-gray-500">{{ $radio->type_radio ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">URL Stream</dt>
                        <dd class="mt-1 text-gray-500 break-all text-sm">{{ $radio->link_radio ?? '-' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Descripcion</dt>
                        <dd class="mt-1 text-gray-500">{{ $radio->description ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Tags</dt>
                        <dd class="mt-1 text-gray-500">{{ $radio->tags ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Direccion</dt>
                        <dd class="mt-1 text-gray-500">{{ $radio->address ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Social Links --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3 mb-5">Redes Sociales</h3>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Sitio Web</dt>
                        <dd class="mt-1">
                            @if($radio->url_website)
                                <a href="{{ $radio->url_website }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all">{{ $radio->url_website }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Facebook</dt>
                        <dd class="mt-1">
                            @if($radio->url_facebook)
                                <a href="{{ $radio->url_facebook }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all">{{ $radio->url_facebook }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Twitter</dt>
                        <dd class="mt-1">
                            @if($radio->url_twitter)
                                <a href="{{ $radio->url_twitter }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all">{{ $radio->url_twitter }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Instagram</dt>
                        <dd class="mt-1">
                            @if($radio->url_instagram)
                                <a href="{{ $radio->url_instagram }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all">{{ $radio->url_instagram }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Image --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 text-center">
                <img src="{{ $radio->optimized_logo_url }}" alt="{{ $radio->name }}" class="w-32 h-32 rounded-2xl object-cover border border-surface-300 mx-auto mb-4">
            </div>

            {{-- Status --}}
            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Estado</h3>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Estado</span>
                    @if($radio->isActive)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">Activa</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-surface-300">Inactiva</span>
                    @endif
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Destacada</span>
                    @if($radio->isFeatured)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200">Si</span>
                    @else
                        <span class="text-gray-400 text-xs">No</span>
                    @endif
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Rating</span>
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-sm {{ $i <= $radio->rating ? 'text-amber-500' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Creado</span>
                    <span class="text-xs text-gray-400">{{ $radio->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Actualizado</span>
                    <span class="text-xs text-gray-400">{{ $radio->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
