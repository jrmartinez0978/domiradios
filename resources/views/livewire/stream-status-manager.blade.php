<div class="fi-widget flex flex-col gap-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-4">
        <h2 class="text-xl font-bold tracking-tight">
            Emisoras con Streams Caídos
            @if($totalOfflineRadios > 0)
                <span class="text-danger-500 font-bold">({{ $totalOfflineRadios }})</span>
            @else
                <span class="text-success-500 font-bold">(0)</span>
            @endif
        </h2>
        
        <div class="flex flex-wrap items-center gap-2">
            <button 
                wire:click="$refresh"
                wire:loading.attr="disabled"
                class="fi-btn fi-btn-color-success fi-btn-size-md fi-btn-text-nowrap fi-btn-icon-start fi-btn-with-icon fi-btn-icon-size-md fi-btn-with-icon-and-label px-3 py-2 text-sm font-medium rounded-lg border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 focus:ring-primary-500 disabled:opacity-70 inline-flex items-center justify-center gap-2"
            >
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39 5.5 5.5 0 01-9.9-2.5l-.024-.03-2.45-2.45v2.433a.75.75 0 01-1.5 0V4.25a.75.75 0 01.75-.75h4.243a.75.75 0 010 1.5H4.81l2.702 2.702a7 7 0 0010.8 2.972z" clip-rule="evenodd" />
                </svg>
                <span>Actualizar</span>
            </button>
            
            <button 
                wire:click="resetAllStreams"
                wire:confirm="¿Estás seguro que deseas restablecer todos los streams marcados como caídos?"
                wire:loading.attr="disabled"
                class="fi-btn fi-btn-color-primary fi-btn-size-md fi-btn-text-nowrap fi-btn-icon-start fi-btn-with-icon fi-btn-icon-size-md fi-btn-with-icon-and-label px-3 py-2 text-sm font-medium rounded-lg border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 focus:ring-primary-500 disabled:opacity-70 inline-flex items-center justify-center gap-2"
            >
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.74v.443c-.572.055-1.14.122-1.706.2C2.606 4.326 1.5 5.37 1.5 6.52v3.733c0 1.54.707 2.92 1.81 3.835a23.35 23.35 0 003.69 2.48c.5.23.9.42 1.21.54.3.12.65.2 1.02.2.37 0 .72-.08 1.03-.2.3-.12.7-.31 1.2-.54a23.35 23.35 0 003.69-2.48c1.1-.915 1.81-2.294 1.81-3.835V6.52c0-1.15-1.11-2.194-2.8-2.577a41.9 41.9 0 00-1.7-.2v-.442A2.75 2.75 0 0011.25 1h-2.5zM10 4.25c-.75 0-1.5.06-2.25.17v2.58h4.5v-2.58c-.75-.11-1.5-.17-2.25-.17zm-4.5 4.5v2.5a.75.75 0 01-1.5 0v-2.5a.75.75 0 011.5 0zm8.5 0a.75.75 0 01.75.75v2.5a.75.75 0 01-1.5 0v-2.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                </svg>
                <span>Restablecer todos</span>
            </button>
            
            <button 
                wire:click="checkAllStreams"
                wire:loading.attr="disabled"
                class="fi-btn fi-btn-color-info fi-btn-size-md fi-btn-text-nowrap fi-btn-icon-start fi-btn-with-icon fi-btn-icon-size-md fi-btn-with-icon-and-label px-3 py-2 text-sm font-medium rounded-lg border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 focus:ring-primary-500 disabled:opacity-70 inline-flex items-center justify-center gap-2"
            >
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 15a7 7 0 110-14 7 7 0 010 14z" />
                    <path d="M10 5a1 1 0 00-1 1v5a1 1 0 102 0V6a1 1 0 00-1-1z" />
                </svg>
                <span>Verificar todos</span>
            </button>
        </div>
    </div>
    
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
                                        {{ $this->formatDateForHumans($radio->last_stream_failure) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $radio->stream_failure_count > 5 ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $radio->stream_failure_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center space-x-1">
                                        <a 
                                            href="{{ $radio->link_radio }}" 
                                            target="_blank"
                                            class="fi-btn fi-btn-size-xs fi-btn-color-gray fi-btn-text-nowrap fi-btn-icon-start fi-btn-with-icon fi-btn-icon-size-md fi-btn-with-icon-and-label px-2 py-1 text-xs font-medium rounded-lg border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 focus:ring-primary-500 disabled:opacity-70 inline-flex items-center justify-center gap-2"
                                            title="Abrir stream"
                                        >
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        
                                        <a 
                                            href="/panel/radios/{{ $radio->id }}/edit"
                                            class="fi-btn fi-btn-size-xs fi-btn-color-warning fi-btn-text-nowrap fi-btn-icon-start fi-btn-with-icon fi-btn-icon-size-md fi-btn-with-icon-and-label px-2 py-1 text-xs font-medium rounded-lg border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 focus:ring-primary-500 disabled:opacity-70 inline-flex items-center justify-center gap-2"
                                            title="Editar emisora"
                                        >
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                            </svg>
                                        </a>
                                        
                                        <button 
                                            wire:click="checkIndividualStream({{ $radio->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="checkIndividualStream({{ $radio->id }})"
                                            class="fi-btn fi-btn-size-xs fi-btn-color-success fi-btn-text-nowrap fi-btn-icon-start fi-btn-with-icon fi-btn-icon-size-md fi-btn-with-icon-and-label px-2 py-1 text-xs font-medium rounded-lg border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 focus:ring-primary-500 disabled:opacity-70 inline-flex items-center justify-center gap-2"
                                            title="Verificar ahora"
                                        >
                                            <span class="flex items-center gap-2">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
                                                </svg>
                                                <span class="sr-only">Verificar ahora</span>
                                            </span>
                                        </button>
                                        
                                        <button 
                                            wire:click="resetStreamStatus({{ $radio->id }})"
                                            wire:confirm="¿Estás seguro que deseas restablecer el estado de este stream?"
                                            wire:loading.attr="disabled"
                                            wire:target="resetStreamStatus({{ $radio->id }})"
                                            class="fi-btn fi-btn-size-xs fi-btn-color-primary fi-btn-text-nowrap fi-btn-icon-start fi-btn-with-icon fi-btn-icon-size-md fi-btn-with-icon-and-label px-2 py-1 text-xs font-medium rounded-lg border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 focus:ring-primary-500 disabled:opacity-70 inline-flex items-center justify-center gap-2"
                                            title="Restablecer estado"
                                        >
                                            <span class="flex items-center gap-2">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39 5.5 5.5 0 01-9.9-2.5l-.024-.03-2.45-2.45v2.433a.75.75 0 01-1.5 0V4.25a.75.75 0 01.75-.75h4.243a.75.75 0 010 1.5H4.81l2.702 2.702a7 7 0 0010.8 2.972z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="sr-only">Restablecer estado</span>
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('notify', (event) => {
                    const type = event.type || 'success';
                    const message = event.message || 'Operación completada correctamente';
                    
                    // Mostrar notificación con Alpine.js o el sistema de notificaciones que estés usando
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type, message }
                    }));
                });
            });
        </script>
    @endpush
</div>
