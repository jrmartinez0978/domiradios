@extends('layouts.admin')

@section('title', 'Configuracion')

@section('page-header')
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Configuracion</h2>
        <p class="text-gray-500 text-sm mt-1">Ajustes avanzados del sistema</p>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.configs.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-surface-300 pb-3">Configuracion Avanzada</h3>

                @php
                    $fields = $config->toArray();
                    $excluded = ['id', 'created_at', 'updated_at', 'deleted_at'];
                @endphp

                @foreach($fields as $key => $value)
                    @if(!in_array($key, $excluded))
                        @if(is_bool($value) || $value === '0' || $value === '1')
                            <x-admin.form-toggle
                                :name="$key"
                                :label="ucfirst(str_replace('_', ' ', $key))"
                                :checked="(bool) $value"
                            />
                        @elseif(is_string($value) && strlen($value) > 200)
                            <x-admin.form-textarea
                                :name="$key"
                                :label="ucfirst(str_replace('_', ' ', $key))"
                                :value="$value"
                                rows="4"
                            />
                        @else
                            <x-admin.form-input
                                :name="$key"
                                :label="ucfirst(str_replace('_', ' ', $key))"
                                :value="(string) $value"
                            />
                        @endif
                    @endif
                @endforeach
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Configuracion
                </button>
            </div>
        </form>
    </div>
@endsection
