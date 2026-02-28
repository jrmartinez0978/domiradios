{{-- Player Bar Global - Fixed bottom --}}
<div
    x-data="{
        expanded: false,
        get show() { return $store.player.isActive; }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full"
    x-cloak
    class="fixed bottom-0 inset-x-0 z-50"
>
    {{-- Desktop Player --}}
    <div class="hidden md:block bg-white/95 backdrop-blur-sm border-t border-surface-300 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center gap-4">
            {{-- Thumbnail + Info --}}
            <div class="flex items-center gap-3 min-w-0 w-72">
                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-surface-200">
                    <img
                        x-show="$store.player.radioImage"
                        :src="$store.player.radioImage"
                        :alt="$store.player.radioName"
                        class="w-full h-full object-cover"
                    >
                </div>
                <div class="min-w-0">
                    <a :href="'/emisoras/' + $store.player.radioSlug" wire:navigate class="text-sm font-semibold text-gray-800 truncate block hover:text-primary transition-colors" x-text="$store.player.radioName"></a>
                    <p class="text-xs text-gray-500 truncate" x-text="$store.player.currentTrack || $store.player.radioFrequency || 'En vivo'"></p>
                </div>
            </div>

            {{-- Center Controls --}}
            <div class="flex-1 flex items-center justify-center gap-4">
                {{-- Play/Pause --}}
                <button
                    @click="$store.player.toggle()"
                    class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-200"
                    :class="$store.player.isPlaying ? 'bg-primary hover:bg-primary-600 shadow-lg shadow-primary/25' : 'bg-surface-200 hover:bg-surface-300'"
                >
                    <template x-if="$store.player.isConnecting">
                        <i class="fas fa-circle-notch fa-spin text-white text-lg"></i>
                    </template>
                    <template x-if="$store.player.isPlaying">
                        <i class="fas fa-pause text-white text-lg"></i>
                    </template>
                    <template x-if="$store.player.state === 'offline'">
                        <i class="fas fa-exclamation-triangle text-gray-600 text-lg"></i>
                    </template>
                    <template x-if="$store.player.state === 'idle' && $store.player.radioId">
                        <i class="fas fa-play text-gray-600 text-lg ml-0.5"></i>
                    </template>
                </button>

                {{-- Equalizer when playing --}}
                <div x-show="$store.player.isPlaying" class="flex items-end gap-[3px] h-5">
                    <span class="equalizer-bar animate-equalizer-1"></span>
                    <span class="equalizer-bar animate-equalizer-2"></span>
                    <span class="equalizer-bar animate-equalizer-3"></span>
                    <span class="equalizer-bar animate-equalizer-1"></span>
                </div>

                {{-- Current track display --}}
                <div x-show="$store.player.currentTrack" class="text-sm text-gray-500 max-w-xs truncate">
                    <i class="fas fa-music text-primary mr-1"></i>
                    <span x-text="$store.player.currentTrack"></span>
                </div>
            </div>

            {{-- Right Controls --}}
            <div class="flex items-center gap-3 w-72 justify-end">
                {{-- Listeners --}}
                <div x-show="$store.player.listeners > 0" class="text-xs text-gray-500 flex items-center gap-1">
                    <i class="fas fa-headphones"></i>
                    <span x-text="$store.player.listeners"></span>
                </div>

                {{-- Favorite --}}
                <button @click="$store.player.toggleFavorite()" class="p-2 rounded-lg hover:bg-primary-50 transition-colors">
                    <i class="fas fa-heart text-sm" :class="$store.player.isFavorite ? 'text-accent' : 'text-gray-400'"></i>
                </button>

                {{-- Volume --}}
                <div class="flex items-center gap-2 w-32">
                    <i class="fas fa-volume-up text-gray-400 text-sm"></i>
                    <input
                        type="range"
                        min="0" max="100"
                        :value="$store.player.volume"
                        @input="$store.player.setVolume($event.target.value)"
                        class="w-full h-1 bg-surface-300 rounded-full appearance-none cursor-pointer
                               [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-3 [&::-webkit-slider-thumb]:h-3
                               [&::-webkit-slider-thumb]:bg-primary [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:cursor-pointer"
                    >
                </div>

                {{-- Stop --}}
                <button @click="$store.player.stop()" class="p-2 rounded-lg hover:bg-surface-200 transition-colors text-gray-400 hover:text-gray-700">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Player --}}
    <div class="md:hidden">
        {{-- Compact bar --}}
        <div
            x-show="!expanded"
            @click="expanded = true"
            class="bg-white/95 backdrop-blur-sm border-t border-surface-300 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] px-4 h-16 flex items-center gap-3 cursor-pointer"
        >
            <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-surface-200">
                <img x-show="$store.player.radioImage" :src="$store.player.radioImage" :alt="$store.player.radioName" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate" x-text="$store.player.radioName"></p>
                <p class="text-xs text-gray-500 truncate" x-text="$store.player.currentTrack || 'En vivo'"></p>
            </div>
            <button @click.stop="$store.player.toggle()" class="w-10 h-10 rounded-full flex items-center justify-center"
                :class="$store.player.isPlaying ? 'bg-primary' : 'bg-surface-200'">
                <template x-if="$store.player.isConnecting">
                    <i class="fas fa-circle-notch fa-spin text-white"></i>
                </template>
                <template x-if="$store.player.isPlaying">
                    <i class="fas fa-pause text-white"></i>
                </template>
                <template x-if="!$store.player.isPlaying && !$store.player.isConnecting">
                    <i class="fas fa-play text-gray-600 ml-0.5"></i>
                </template>
            </button>
            {{-- Equalizer --}}
            <div x-show="$store.player.isPlaying" class="flex items-end gap-[2px] h-4">
                <span class="equalizer-bar animate-equalizer-1"></span>
                <span class="equalizer-bar animate-equalizer-2"></span>
                <span class="equalizer-bar animate-equalizer-3"></span>
            </div>
        </div>

        {{-- Expanded view --}}
        <div
            x-show="expanded"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            class="bg-white border-t border-surface-300 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] p-6"
        >
            <button @click="expanded = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 p-2">
                <i class="fas fa-chevron-down"></i>
            </button>

            <div class="flex flex-col items-center gap-4">
                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-surface-200 shadow-lg">
                    <img x-show="$store.player.radioImage" :src="$store.player.radioImage" :alt="$store.player.radioName" class="w-full h-full object-cover">
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-bold text-gray-800" x-text="$store.player.radioName"></h3>
                    <p class="text-sm text-gray-500 mt-1" x-text="$store.player.currentTrack || $store.player.radioFrequency || 'En vivo'"></p>
                </div>

                <div class="flex items-center gap-6">
                    <button @click="$store.player.toggleFavorite()" class="p-3">
                        <i class="fas fa-heart text-xl" :class="$store.player.isFavorite ? 'text-accent' : 'text-gray-400'"></i>
                    </button>
                    <button @click="$store.player.toggle()" class="w-16 h-16 rounded-full flex items-center justify-center"
                        :class="$store.player.isPlaying ? 'bg-primary shadow-lg shadow-primary/25' : 'bg-surface-200'">
                        <template x-if="$store.player.isConnecting">
                            <i class="fas fa-circle-notch fa-spin text-white text-2xl"></i>
                        </template>
                        <template x-if="$store.player.isPlaying">
                            <i class="fas fa-pause text-white text-2xl"></i>
                        </template>
                        <template x-if="!$store.player.isPlaying && !$store.player.isConnecting">
                            <i class="fas fa-play text-gray-600 text-2xl ml-1"></i>
                        </template>
                    </button>
                    <button @click="$store.player.stop()" class="p-3 text-gray-400 hover:text-gray-700">
                        <i class="fas fa-stop text-xl"></i>
                    </button>
                </div>

                {{-- Volume slider --}}
                <div class="w-full flex items-center gap-3 px-4">
                    <i class="fas fa-volume-down text-gray-400"></i>
                    <input type="range" min="0" max="100" :value="$store.player.volume"
                        @input="$store.player.setVolume($event.target.value)"
                        class="flex-1 h-1 bg-surface-300 rounded-full appearance-none
                               [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:h-4
                               [&::-webkit-slider-thumb]:bg-primary [&::-webkit-slider-thumb]:rounded-full">
                    <i class="fas fa-volume-up text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>
