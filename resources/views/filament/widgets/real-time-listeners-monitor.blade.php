<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-semibold mb-2">{{ $this->heading }}</h2>

        <div class="max-h-70 overflow-y-auto">
            @php
                $data = $this->getListenersData();
            @endphp

            @foreach ($data as $cityName => $stations)
                <div x-data="{ open: false }" class="mb-2">
                    <div @click="open = !open" class="cursor-pointer bg-gray-200 dark:bg-gray-800 p-2 rounded">
                        <h3 class="text-base font-medium text-gray-900 dark:text-gray-100">
                            {{ $cityName }} (Total Oyentes: {{ array_sum(array_column($stations, 'listeners')) }})
                        </h3>
                    </div>
                    <div x-show="open" class="mt-1">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Emisora</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Oyentes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($stations as $station)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $station['radio_name'] }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $station['listeners'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

    </x-filament::card>
</x-filament::widget>



