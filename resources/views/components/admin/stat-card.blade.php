@props(['title', 'value', 'icon', 'color' => 'red', 'trend' => null])

@php
    $colorMap = [
        'red'   => ['bg' => 'bg-accent-red/20', 'text' => 'text-accent-red', 'border' => 'border-accent-red/30'],
        'blue'  => ['bg' => 'bg-accent-blue/20', 'text' => 'text-accent-blue', 'border' => 'border-accent-blue/30'],
        'green' => ['bg' => 'bg-accent-green/20', 'text' => 'text-accent-green', 'border' => 'border-accent-green/30'],
        'amber' => ['bg' => 'bg-accent-amber/20', 'text' => 'text-accent-amber', 'border' => 'border-accent-amber/30'],
    ];
    $colors = $colorMap[$color] ?? $colorMap['red'];
@endphp

<div class="glass-card p-5 flex items-start gap-4">
    {{-- Icon --}}
    <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $colors['bg'] }} {{ $colors['border'] }} border flex items-center justify-center">
        <i class="{{ $icon }} {{ $colors['text'] }} text-lg"></i>
    </div>

    {{-- Content --}}
    <div class="flex-1 min-w-0">
        <p class="text-xs font-medium uppercase tracking-wider text-dark-400 mb-1">{{ $title }}</p>
        <div class="flex items-end gap-2">
            <span class="text-2xl font-bold text-white">{{ $value }}</span>
            @if($trend)
                @php
                    $trendValue = floatval($trend);
                    $isUp = $trendValue >= 0;
                @endphp
                <span class="flex items-center gap-0.5 text-xs font-medium {{ $isUp ? 'text-accent-green' : 'text-accent-red' }} mb-1">
                    <i class="fas fa-arrow-{{ $isUp ? 'up' : 'down' }} text-[10px]"></i>
                    {{ abs($trendValue) }}%
                </span>
            @endif
        </div>
    </div>
</div>
