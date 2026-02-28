@props(['name', 'label' => null, 'options' => [], 'value' => '', 'required' => false, 'placeholder' => 'Seleccionar...'])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-600 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-primary">*</span> @endif
        </label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'glass-input w-full']) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $optVal => $optLabel)
            <option value="{{ $optVal }}" {{ old($name, $value) == $optVal ? 'selected' : '' }}>
                {{ $optLabel }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
