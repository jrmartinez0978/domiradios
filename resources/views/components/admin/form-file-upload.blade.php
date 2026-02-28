@props(['name', 'label' => null, 'accept' => 'image/*', 'currentImage' => null])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-600 mb-1.5">{{ $label }}</label>
    @endif

    @if($currentImage)
        <div class="mb-3">
            <p class="text-xs text-gray-500 mb-1">Imagen actual:</p>
            <img src="{{ Storage::url($currentImage) }}" alt="Current" class="w-24 h-24 rounded-xl object-cover border border-surface-300">
        </div>
    @endif

    <div class="relative">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="{{ $accept }}"
            {{ $attributes->merge(['class' => 'block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-surface-300 file:text-sm file:font-semibold file:bg-surface-100 file:text-gray-700 hover:file:bg-surface-200 file:cursor-pointer file:transition-colors']) }}
        >
    </div>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
