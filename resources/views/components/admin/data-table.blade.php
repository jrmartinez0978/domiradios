@props(['headers' => [], 'checkboxes' => false])

<div class="bg-white rounded-2xl border border-surface-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-surface-100 border-b border-surface-300">
                    @if($checkboxes)
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox"
                                   class="w-4 h-4 rounded bg-white border-surface-300 text-primary focus:ring-primary/50 focus:ring-offset-0"
                                   x-data
                                   @change="document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = $event.target.checked)">
                        </th>
                    @endif
                    @foreach($headers as $header)
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 whitespace-nowrap">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-300">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
