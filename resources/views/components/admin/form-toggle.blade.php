@props(['name', 'label' => null, 'checked' => false])

<div class="flex items-center gap-3" x-data="{ on: {{ old($name, $checked) ? 'true' : 'false' }} }">
    <input type="hidden" name="{{ $name }}" :value="on ? '1' : '0'">
    <button
        type="button"
        @click="on = !on"
        :class="on ? 'bg-accent-red' : 'bg-dark-600'"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-accent-red/50"
    >
        <span
            :class="on ? 'translate-x-5' : 'translate-x-0'"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
        ></span>
    </button>
    @if($label)
        <label class="text-sm font-medium text-dark-300 cursor-pointer" @click="on = !on">{{ $label }}</label>
    @endif
    @error($name)
        <p class="mt-1 text-xs text-accent-red">{{ $message }}</p>
    @enderror
</div>
