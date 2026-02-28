@props(['title', 'value', 'icon', 'color' => 'red', 'trend' => null])

@php
    $colorMap = [
        'red'   => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'border' => 'border-primary/20'],
        'blue'  => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
        'green' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
    ];
    $colors = $colorMap[$color] ?? $colorMap['red'];
@endphp

<div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-5 flex items-start gap-4">
    {{-- Icon --}}
    <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $colors['bg'] }} {{ $colors['border'] }} border flex items-center justify-center">
        <i class="{{ $icon }} {{ $colors['text'] }} text-lg"></i>
    </div>

    {{-- Content --}}
    <div class="flex-1 min-w-0">
        <p class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">{{ $title }}</p>
        <div class="flex items-end gap-2">
            <span class="text-2xl font-bold text-gray-800">{{ $value }}</span>
            @if($trend)
                @php
                    $trendValue = floatval($trend);
                    $isUp = $trendValue >= 0;
                @endphp
                <span class="flex items-center gap-0.5 text-xs font-medium {{ $isUp ? 'text-emerald-600' : 'text-red-600' }} mb-1">
                    <i class="fas fa-arrow-{{ $isUp ? 'up' : 'down' }} text-[10px]"></i>
                    {{ abs($trendValue) }}%
                </span>
            @endif
        </div>
    </div>
</div>
