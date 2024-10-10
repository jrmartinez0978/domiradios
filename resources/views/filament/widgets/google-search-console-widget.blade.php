<x-filament::widget>
    <x-filament::card>
        <h2 class="text-xl font-bold mb-4">Datos de Google Search Console</h2>

        @if($data)
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consulta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clics</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $row->keys[0] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $row->clicks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No se encontraron datos.</p>
        @endif
    </x-filament::card>
</x-filament::widget>

