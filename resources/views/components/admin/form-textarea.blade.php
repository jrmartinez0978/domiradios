@props(['name', 'label' => null, 'value' => '', 'required' => false, 'rows' => 4, 'placeholder' => ''])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-dark-300 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-accent-red">*</span> @endif
        </label>
    @endif
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'glass-input w-full']) }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="mt-1 text-xs text-accent-red">{{ $message }}</p>
    @enderror
</div>
