<x-filament::widget>
    <x-filament::card>
        <h2 class="text-xl font-bold mb-4">{{ $this->heading }}</h2>

        @php
            $data = $this->getListenersData();
        @endphp

        @foreach ($data as $cityName => $stations)
            <div x-data="{ open: false }" class="mb-4">
                <div @click="open = !open" class="cursor-pointer bg-gray-200 dark:bg-gray-800 p-2 rounded">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $cityName }} (Total Oyentes: {{ array_sum(array_column($stations, 'listeners')) }})
                    </h3>
                </div>
                <div x-show="open" class="mt-2">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Emisora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Oyentes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($stations as $station)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $station['radio_name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $station['listeners'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </x-filament::card>
</x-filament::widget>


