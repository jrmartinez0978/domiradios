@props(['name', 'label' => null, 'value' => 0, 'max' => 5])

<div x-data="{ rating: {{ old($name, $value) }} }">
    @if($label)
        <label class="block text-sm font-medium text-gray-600 mb-1.5">{{ $label }}</label>
    @endif
    <input type="hidden" name="{{ $name }}" :value="rating">
    <div class="flex items-center gap-1">
        @for($i = 1; $i <= $max; $i++)
            <button
                type="button"
                @click="rating = {{ $i }}"
                :class="rating >= {{ $i }} ? 'text-amber-500' : 'text-gray-300'"
                class="text-xl transition-colors hover:text-amber-500 focus:outline-none"
            >
                <i class="fas fa-star"></i>
            </button>
        @endfor
        <span class="ml-2 text-sm text-gray-500" x-text="rating + '/{{ $max }}'"></span>
    </div>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
