@extends('layouts.admin')

@section('title', 'Editar Emisora')

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.radios.index') }}" class="btn-glass !px-3 !py-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Editar Emisora</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $radio->name }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.radios.update', $radio) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Informacion General</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="name" label="Nombre" required :value="$radio->name" />
                        <x-admin.form-input name="slug" label="Slug" :value="$radio->slug" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="bitrate" label="Frecuencia / Bitrate" :value="$radio->bitrate" />
                        <x-admin.form-input name="link_radio" label="URL del Stream" required :value="$radio->link_radio" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-select name="source_radio" label="Fuente" :options="['Shoutcast' => 'Shoutcast', 'Icecast' => 'Icecast', 'SonicPanel' => 'SonicPanel', 'JRMStream' => 'JRMStream', 'AzuraCast' => 'AzuraCast', 'HTML5' => 'HTML5', 'Other' => 'Other']" :value="$radio->source_radio" required />
                        <x-admin.form-select name="type_radio" label="Tipo Audio" :options="['audio/mpeg' => 'audio/mpeg', 'audio/aac' => 'audio/aac', 'audio/ogg' => 'audio/ogg']" :value="$radio->type_radio" required />
                    </div>

                    <x-admin.form-textarea name="description" label="Descripcion" rows="4" :value="$radio->description" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="tags" label="Tags" :value="$radio->tags" />
                        <x-admin.form-input name="address" label="Direccion" :value="$radio->address" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Géneros Musicales</label>
                            @php $selectedGenres = $radio->genres->where('type', 'genre')->pluck('id')->toArray(); @endphp
                            <select name="genres[]" multiple class="w-full rounded-xl border border-surface-300 px-3 py-2 text-sm focus:border-primary focus:ring-primary">
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}" {{ in_array($genre->id, $selectedGenres) ? 'selected' : '' }}>{{ $genre->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Mantén Ctrl para seleccionar varios</p>
                        </div>
                        @php $selectedCity = $radio->genres->where('type', 'city')->first()?->id; @endphp
                        <x-admin.form-select name="city_id" label="Ciudad" :options="$cities->pluck('name', 'id')->toArray()" :value="$selectedCity" placeholder="Seleccionar ciudad" />
                    </div>
                </div>

                {{-- Social & Web --}}
                <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Redes Sociales y Web</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="url_website" label="Sitio Web" type="url" :value="$radio->url_website" />
                        <x-admin.form-input name="url_facebook" label="Facebook" type="url" :value="$radio->url_facebook" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="url_twitter" label="Twitter" type="url" :value="$radio->url_twitter" />
                        <x-admin.form-input name="url_instagram" label="Instagram" type="url" :value="$radio->url_instagram" />
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status --}}
                <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Estado</h3>

                    <x-admin.form-toggle name="isActive" label="Activa" :checked="$radio->isActive" />
                    <x-admin.form-toggle name="isFeatured" label="Destacada" :checked="$radio->isFeatured" />
                    <x-admin.form-rating name="rating" label="Rating" :value="$radio->rating" />
                </div>

                {{-- Image --}}
                <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Imagen</h3>
                    <x-admin.form-file-upload name="img" label="Logo de la emisora" :currentImage="$radio->img" />
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Actualizar Emisora
                </button>
            </div>
        </div>
    </form>
@endsection
