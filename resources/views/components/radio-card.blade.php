@props(['radio', 'loop' => null])

@php
    $isRTC = $radio->source_radio === 'JRMStream';
    $isShoutcast = $radio->source_radio === 'Shoutcast';
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
    $genreNames = $radio->genres->where('type', 'genre')->pluck('name')->implode(', ');
    $cityName = $radio->genres->where('type', 'city')->first()?->name;
    $location = $radio->address ?: ($cityName ?: 'R.D.');
@endphp

<article
    x-data
    class="group relative rounded-xl bg-white border border-primary/40 shadow-[0_0_12px_rgba(0,80,70,0.25)] hover:shadow-[0_0_25px_rgba(0,80,70,0.45)] hover:border-primary/70 transition-all duration-300 hover:-translate-y-1 overflow-hidden"
    itemscope itemtype="https://schema.org/RadioStation"
>
    {{-- Schema.org --}}
    <span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" hidden>
        <meta itemprop="addressLocality" content="{{ !empty($radio->address) ? $radio->address : (!empty($radio->city) ? $radio->city : 'República Dominicana') }}">
        <meta itemprop="addressCountry" content="República Dominicana">
    </span>
    <meta itemprop="url" content="{{ route('emisoras.show', ['slug' => $radio->slug]) }}">

    {{-- TOP badge (top-left corner) --}}
    @if($radio->isFeatured)
    <div class="absolute top-2 left-2 z-30 flex items-center gap-1">
        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-amber-400 text-gray-900 text-[9px] font-black rounded-full uppercase tracking-wider shadow-sm">
            <i class="fas fa-star text-[7px]"></i> Top
        </span>
        @if($isRTC)
        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-accent text-white text-[9px] font-bold rounded-full shadow-sm">
            <i class="fas fa-bolt text-[7px]"></i> HD
        </span>
        @endif
    </div>
    @elseif($isRTC)
    <div class="absolute top-2 left-2 z-30">
        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-accent text-white text-[9px] font-bold rounded-full shadow-sm">
            <i class="fas fa-bolt text-[7px]"></i> HD
        </span>
    </div>
    @endif

    {{-- LIVE/FUERA badge (top-right corner) --}}
    <div class="absolute top-2 right-2 z-30">
        @if($radio->is_stream_active === false)
        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-gray-600 text-white text-[8px] font-bold rounded-full shadow-sm">
            <span class="relative flex h-1.5 w-1.5">
                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white/50"></span>
            </span>
            FUERA
        </span>
        @else
        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-red-500 text-white text-[8px] font-bold rounded-full shadow-sm">
            <span class="relative flex h-1.5 w-1.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span>
            </span>
            LIVE
        </span>
        @endif
    </div>

    {{-- PLAY/STOP button (center of image area) --}}
    <button
        @click.prevent.stop="
            if ($store.player && $store.player.radioId == {{ $radio->id }} && $store.player.isPlaying) {
                $store.player.stop();
            } else {
                $store.player.play({{ Js::from($playData) }});
            }
        "
        class="absolute z-30 w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 shadow-lg cursor-pointer hover:scale-110"
        style="top: calc(50% - 3rem); left: 50%; transform: translate(-50%, -50%);"
        x-bind:style="
            ($store.player && $store.player.radioId == {{ $radio->id }} && $store.player.isPlaying)
                ? 'top: calc(50% - 3rem); left: 50%; transform: translate(-50%, -50%); background:white; border:2px solid white; opacity:1;'
                : ($store.player && $store.player.radioId == {{ $radio->id }} && $store.player.isConnecting)
                    ? 'top: calc(50% - 3rem); left: 50%; transform: translate(-50%, -50%); background:rgba(0,0,0,0.5); border:2px solid rgba(255,255,255,0.7); opacity:1;'
                    : 'top: calc(50% - 3rem); left: 50%; transform: translate(-50%, -50%); background:rgba(0,0,0,0.5); border:2px solid rgba(255,255,255,0.7);'
        "
        title="{{ $radio->name }} - Reproducir"
    >
        <i class="fas fa-play text-white text-base ml-0.5"
           x-show="!($store.player && $store.player.radioId == {{ $radio->id }} && ($store.player.isPlaying || $store.player.isConnecting))"
        ></i>
        <i class="fas fa-stop text-primary text-base"
           x-show="$store.player && $store.player.radioId == {{ $radio->id }} && $store.player.isPlaying"
           x-cloak
        ></i>
        <i class="fas fa-circle-notch fa-spin text-white text-base"
           x-show="$store.player && $store.player.radioId == {{ $radio->id }} && $store.player.isConnecting"
           x-cloak
        ></i>
    </button>

    {{-- Image area --}}
    <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" wire:navigate class="block aspect-square overflow-hidden bg-gray-100">
        <img
            src="{{ Storage::url($radio->img) }}"
            alt="{{ $radio->name }} {{ $radio->bitrate }} - Radio en vivo"
            class="w-full h-full object-contain p-2 transition-transform duration-500 ease-out group-hover:scale-105"
            itemprop="image"
            width="300" height="300"
            @if($loop && $loop->index >= 6) loading="lazy" @endif
        >
    </a>

    {{-- Content area --}}
    <a href="{{ route('emisoras.show', ['slug' => $radio->slug]) }}" wire:navigate class="block p-3">
        @if($radio->bitrate)
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-xs font-bold text-primary" itemprop="frequency">{{ $radio->bitrate }}</span>
            <span class="text-[9px] font-semibold uppercase tracking-wider {{ $isRTC ? 'text-accent' : ($isShoutcast ? 'text-blue-500' : 'text-gray-400') }}">
                @if($isRTC) JRMStream @elseif($isShoutcast) Shoutcast @else Online @endif
            </span>
        </div>
        @endif

        <h3 class="font-bold text-gray-800 text-sm leading-tight line-clamp-1 group-hover:text-primary transition-colors" itemprop="name">
            {{ $radio->name }}
        </h3>

        <div class="flex items-center justify-between mt-1">
            <span class="text-gray-400 text-[11px] line-clamp-1">
                @if($genreNames)
                    {{ Str::limit($genreNames, 20) }}
                @else
                    {{ $radio->source_radio ?? 'Online' }}
                @endif
            </span>
            <span class="flex items-center gap-0.5 text-gray-400 text-[10px] flex-shrink-0">
                <i class="fas fa-map-marker-alt text-[8px]"></i>
                <span itemprop="location">{{ Str::limit($location, 10) }}</span>
            </span>
        </div>

        <div class="flex items-center gap-0.5 mt-1.5">
            @for($i = 1; $i <= 5; $i++)
                <i class="fa fa-star text-[9px] {{ $i <= $radio->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
            @endfor
        </div>
    </a>

    <span itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating" class="sr-only">
        <meta itemprop="ratingValue" content="{{ $radio->rating }}">
        <meta itemprop="bestRating" content="5">
        <meta itemprop="worstRating" content="1">
        <meta itemprop="ratingCount" content="{{ isset($radio->rating_count) ? (int)$radio->rating_count : 1 }}">
    </span>
</article>
