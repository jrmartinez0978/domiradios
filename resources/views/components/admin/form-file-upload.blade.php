@props(['name', 'label' => null, 'accept' => 'image/*', 'currentImage' => null])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-dark-300 mb-1.5">{{ $label }}</label>
    @endif

    @if($currentImage)
        <div class="mb-3">
            <p class="text-xs text-dark-400 mb-1">Imagen actual:</p>
            <img src="{{ Storage::url($currentImage) }}" alt="Current" class="w-24 h-24 rounded-xl object-cover border border-glass-border">
        </div>
    @endif

    <div class="relative">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="{{ $accept }}"
            {{ $attributes->merge(['class' => 'block w-full text-sm text-dark-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-glass-border file:text-sm file:font-semibold file:bg-glass-white-10 file:text-gray-200 hover:file:bg-glass-white-20 file:cursor-pointer file:transition-colors']) }}
        >
    </div>
    @error($name)
        <p class="mt-1 text-xs text-accent-red">{{ $message }}</p>
    @enderror
</div>
