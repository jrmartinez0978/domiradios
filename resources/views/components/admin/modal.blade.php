@props(['id', 'title' => 'Confirmar'])

<div
    x-data="{ open: false }"
    x-on:open-modal-{{ $id }}.window="open = true"
    x-on:close-modal-{{ $id }}.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-cloak
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm"
        @click="open = false"
    ></div>

    {{-- Modal Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-white rounded-2xl border border-surface-300 shadow-lg w-full max-w-lg p-6" @click.stop>
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
                <button @click="open = false" class="p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-surface-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="text-sm text-gray-600 mb-6">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3">
                <button @click="open = false" class="btn-glass text-sm">
                    Cancelar
                </button>
                @if(isset($confirm))
                    {{ $confirm }}
                @else
                    <button @click="open = false; $dispatch('confirmed-{{ $id }}')" class="btn-primary text-sm !px-4 !py-2">
                        Confirmar
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
