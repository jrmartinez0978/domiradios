<x-filament::section class="filament-widget-offline-radios w-full">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-4">
        <h2 class="text-xl font-bold tracking-tight">
            Emisoras con Streams Caídos
            @php
                $offlineCount = $this->getTotalOfflineRadios();
            @endphp
            @if($offlineCount > 0)
                <span class="text-danger-500 font-bold">({{ $offlineCount }})</span>
            @else
                <span class="text-success-500 font-bold">(0)</span>
            @endif
        </h2>
        
        <div class="flex flex-wrap items-center gap-2">
            <x-filament::button
                wire:click="$refresh"
                color="success"
                size="md"
                icon="heroicon-o-arrow-path"
            >
                Actualizar
            </x-filament::button>
            
            <x-filament::button
                wire:click="resetAllStreams"
                wire:confirm="¿Estás seguro que deseas restablecer todos los streams marcados como caídos?"
                color="primary"
                size="md"
                icon="heroicon-o-trash"
            >
                Restablecer todos
            </x-filament::button>
            
            <x-filament::button
                wire:click="artisan"
                color="info"
                size="md"
                icon="heroicon-o-server"
            >
                Verificar todos
            </x-filament::button>
        </div>
    </div>
    
    @php
        $offlineRadios = $this->getOfflineRadios();
    @endphp
    
    @if($offlineRadios->isEmpty())
        <div class="flex flex-col items-center justify-center py-6 text-center bg-white rounded-lg border border-gray-200">
            <div class="rounded-full bg-emerald-100 p-3 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="text-gray-600">¡Todas las emisoras están funcionando correctamente!</span>
        </div>
    @else
        <div class="overflow-hidden bg-white border border-gray-200 rounded-xl">
            <div class="overflow-x-auto" style="width: 100%">
                <table class="w-full text-sm divide-y table-auto overflow-x-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 font-medium text-left text-gray-600" style="width: 20%">Emisora</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-600" style="width: 40%">URL del Stream</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-600" style="width: 15%">Última caída</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-600" style="width: 10%">Fallos</th>
                            <th class="px-4 py-3 font-medium text-center text-gray-600" style="width: 15%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($offlineRadios as $radio)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">
                                    <div class="flex items-center">
                                        @if(isset($radio->logo) && $radio->logo)
                                            <img src="{{ asset($radio->logo) }}" alt="{{ $radio->name ?? 'Emisora' }}" class="w-8 h-8 mr-3 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 mr-3 rounded-full bg-gradient-to-r from-emerald-400 to-teal-600 flex items-center justify-center text-white font-bold">
                                                {{ substr(($radio->name ?? 'E'), 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-900">{{ $radio->name ?? 'Emisora sin nombre' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600" style="max-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <a href="{{ $radio->link_radio }}" target="_blank" class="text-primary-600 hover:underline hover:text-primary-500" title="{{ $radio->link_radio }}">
                                        {{ $radio->link_radio }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        {{ $this->formatLastChecked($radio->last_stream_failure) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $radio->stream_failure_count > 5 ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $radio->stream_failure_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center space-x-1">
                                        <x-filament::button
                                            tag="a"
                                            href="{{ $radio->link_radio }}"
                                            target="_blank"
                                            size="xs"
                                            color="gray"
                                            icon="heroicon-o-eye"
                                            tooltip="Abrir stream"
                                        />
                                        
                                        <x-filament::button
                                            tag="a"
                                            href="/admin/radios/edit/{{ $radio->id }}"
                                            size="xs"
                                            color="warning"
                                            icon="heroicon-o-pencil"
                                            tooltip="Editar emisora"
                                        />
                                        
                                        <x-filament::button
                                            wire:click="checkIndividualStream({{ $radio->id }})"
                                            size="xs"
                                            color="success"
                                            icon="heroicon-o-signal"
                                            tooltip="Verificar ahora"
                                        />
                                        
                                        <x-filament::button
                                            wire:click="resetStreamStatus({{ $radio->id }})"
                                            wire:confirm="¿Estás seguro que deseas restablecer el estado de este stream?"
                                            size="xs"
                                            color="primary"
                                            icon="heroicon-o-arrow-path"
                                            tooltip="Restablecer estado"
                                        />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('refresh-after-delay', ({delay}) => {
                setTimeout(() => {
                    @this.$refresh();
                }, delay);
            });
        });
    </script>
</x-filament::section>
