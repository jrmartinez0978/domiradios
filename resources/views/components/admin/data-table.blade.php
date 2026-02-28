@props(['headers' => [], 'checkboxes' => false])

<div class="glass-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-dark-700 border-b border-glass-border">
                    @if($checkboxes)
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox"
                                   class="w-4 h-4 rounded bg-dark-600 border-dark-500 text-accent-red focus:ring-accent-red/50 focus:ring-offset-0"
                                   x-data
                                   @change="document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = $event.target.checked)">
                        </th>
                    @endif
                    @foreach($headers as $header)
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-dark-300 whitespace-nowrap">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-glass-border">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
