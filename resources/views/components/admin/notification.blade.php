@props(['type' => 'success', 'message' => ''])

@php
    $config = [
        'success' => ['icon' => 'fas fa-check-circle', 'bg' => 'bg-accent-green/20', 'border' => 'border-accent-green/30', 'text' => 'text-accent-green'],
        'error'   => ['icon' => 'fas fa-times-circle', 'bg' => 'bg-accent-red/20', 'border' => 'border-accent-red/30', 'text' => 'text-accent-red'],
        'warning' => ['icon' => 'fas fa-exclamation-triangle', 'bg' => 'bg-accent-amber/20', 'border' => 'border-accent-amber/30', 'text' => 'text-accent-amber'],
    ];
    $style = $config[$type] ?? $config['success'];
@endphp

<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 5000)"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-8"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-8"
    class="fixed top-20 right-4 z-50 max-w-sm"
>
    <div class="glass-card {{ $style['bg'] }} {{ $style['border'] }} border p-4 flex items-start gap-3 shadow-lg">
        <i class="{{ $style['icon'] }} {{ $style['text'] }} text-lg mt-0.5"></i>
        <div class="flex-1">
            <p class="text-sm text-white font-medium">{{ $message }}</p>
        </div>
        <button @click="show = false" class="text-dark-400 hover:text-white transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
