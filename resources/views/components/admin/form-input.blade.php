@props(['name', 'label' => null, 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => ''])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-dark-300 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-accent-red">*</span> @endif
        </label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'glass-input w-full']) }}
    >
    @error($name)
        <p class="mt-1 text-xs text-accent-red">{{ $message }}</p>
    @enderror
</div>
