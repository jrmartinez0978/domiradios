@props(['radio', 'loop' => null])

@php
    $isRTC = $radio->source_radio === 'RTCStream';
    $playData = [
        'id' => $radio->id,
        'name' => $radio->name,
        'image' => Storage::url($radio->img),
        'slug' => $radio->slug,
        'frequency' => $radio->bitrate,
        'streamUrl' => $radio->link_radio,
        'sourceRadio' => $radio->source_radio,
    ];
    if ($isRTC) {
        $pu = parse_url($radio->link_radio);
        $playData['rtcSlug'] = end(array_filter(explode('/', $pu['path'] ?? ''))) ?: $radio->slug;
        $playData['rtcServer'] = $pu['host'] ?? 'live.rtcstreaming.com';
        $playData['rtcPort'] = $pu['port'] ?? 9000;
    }
@endphp

<article
    x-data="{ hover: false }"
    @mouseenter="hover = true"
    @mouseleave="hover = false"
    class="glass-card-hover group relative overflow-hidden"
    itemscope itemtype="http://schema.org/RadioStation"
>
    {{-- Schema.org microdata --}}
    <span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" hidden>
        <meta itemprop="addressLocality" content="{{ !empty($radio->address) ? $radio->address : (!empty($radio->city) ? $radio->city : 'República Dominicana') }}">
        <meta itemprop="addressCountry" content="República Dominicana">
    </span>
    <meta itemprop="url" content="{{ route('emisoras.show', ['slug' => $radio->slug]) }}">

    {{-- Featured badge --}}
    @if($radio->isFeatured)
    <div class="absolute top-2.5 right-2.5 z-10 badge-featured animate-pulse-glow">
        <i class="fas fa-star text-yellow-400 text-[10px]"></i>
        <span>Destacada</span>
    </div>
    @endif

    {{-- Image --}}
    <div class="aspect-square overflow-hidden relative">
        <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" aria-label="Ver {{ $radio->name }}">
            <img
                src="{{ Storage::url($radio->img) }}"
                alt="{{ $radio->name }} {{ $radio->bitrate }} - Radio en vivo"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                itemprop="image"
                width="300" height="300"
                @if($loop && $loop->index >= 6) loading="lazy" @endif
            >
        </a>
        {{-- Play overlay --}}
        <button
            @click="$store.player.play({{ Js::from($playData) }})"
            class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/40 transition-all duration-300 cursor-pointer"
        >
            <div
                x-show="hover || ($store.player.radioId == {{ $radio->id }} && $store.player.isPlaying)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-75"
                x-transition:enter-end="opacity-100 scale-100"
                class="w-12 h-12 rounded-full flex items-center justify-center backdrop-blur-sm"
                :class="$store.player.radioId == {{ $radio->id }} && $store.player.isPlaying ? 'bg-accent-red glow-red' : 'bg-white/20'"
            >
                <template x-if="$store.player.radioId == {{ $radio->id }} && $store.player.isPlaying">
                    <div class="flex items-end gap-[2px] h-4">
                        <span class="equalizer-bar animate-equalizer-1"></span>
                        <span class="equalizer-bar animate-equalizer-2"></span>
                        <span class="equalizer-bar animate-equalizer-3"></span>
                    </div>
                </template>
                <template x-if="$store.player.radioId == {{ $radio->id }} && $store.player.isConnecting">
                    <i class="fas fa-circle-notch fa-spin text-white text-lg"></i>
                </template>
                <template x-if="!($store.player.radioId == {{ $radio->id }} && ($store.player.isPlaying || $store.player.isConnecting))">
                    <i class="fas fa-play text-white text-lg ml-0.5"></i>
                </template>
            </div>
        </button>
    </div>

    {{-- Content --}}
    <div class="p-3.5">
        <h3 class="font-semibold text-sm text-gray-100 line-clamp-1 mb-1.5" itemprop="name">
            <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" class="hover:text-accent-red transition-colors">
                {{ $radio->name }}
            </a>
        </h3>

        {{-- Rating --}}
        <div class="flex items-center gap-0.5 mb-2">
            @for($i = 1; $i <= 5; $i++)
                <i class="fa fa-star text-[10px] {{ $i <= $radio->rating ? 'text-yellow-400' : 'text-dark-600' }}"></i>
            @endfor
            <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="sr-only">
                <meta itemprop="ratingValue" content="{{ $radio->rating }}">
                <meta itemprop="bestRating" content="5">
                <meta itemprop="worstRating" content="1">
                <meta itemprop="ratingCount" content="{{ isset($radio->rating_count) ? (int)$radio->rating_count : 1 }}">
            </span>
        </div>

        {{-- Meta info --}}
        <div class="space-y-1 text-xs text-dark-400">
            @if($radio->bitrate)
            <p class="flex items-center gap-1.5" itemprop="frequency">
                <i class="fas fa-broadcast-tower text-accent-red w-3 text-center"></i>
                <span class="truncate">{{ $radio->bitrate }}</span>
            </p>
            @endif
            <p class="flex items-center gap-1.5">
                <i class="fas fa-map-marker-alt text-accent-red w-3 text-center"></i>
                <span class="truncate" itemprop="location">{{ $radio->genres->pluck('name')->implode(', ') }}</span>
            </p>
        </div>

        {{-- RTC badge --}}
        @if($isRTC)
        <div class="mt-2 text-[9px] text-center font-semibold text-accent-red/70">
            <i class="fas fa-bolt mr-0.5"></i>RTCStream
        </div>
        @endif
    </div>
</article>
