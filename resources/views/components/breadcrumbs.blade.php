@if (!empty($items))
<nav aria-label="breadcrumb" class="mb-4 text-sm">
    <ol class="flex items-center space-x-1 text-gray-500 rtl:space-x-reverse">
        @foreach ($items as $item)
            <li>
                <div class="flex items-center">
                    @if (!$loop->first)
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                    @endif
                    @if (!$loop->last && isset($item['url']))
                        <a href="{{ $item['url'] }}" class="hover:text-brand-blue hover:underline">{{ $item['name'] }}</a>
                    @else
                        <span class="text-gray-700 font-semibold">{{ $item['name'] }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>

{{-- Solo incluir el script si el schema tiene elementos --}}
@if($schema && !empty(json_decode($schema, true)['itemListElement']))
@push('head_additional')
<script type="application/ld+json">
{!! $schema !!}
</script>
@endpush
@endif

@endif
