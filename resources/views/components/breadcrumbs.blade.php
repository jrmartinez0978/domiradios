{{--
  Breadcrumbs Component - SEO 2025 Optimizado
  Incluye BreadcrumbList Schema automático
--}}

@if (!empty($items))
<nav aria-label="breadcrumb" class="mb-6 text-sm">
    <ol class="flex items-center space-x-1 text-dark-400 rtl:space-x-reverse" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach ($items as $index => $item)
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <div class="flex items-center">
                    @if (!$loop->first)
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                    @endif
                    @if (!$loop->last && isset($item['url']))
                        <a href="{{ $item['url'] }}"
                           wire:navigate
                           class="hover:text-accent-red hover:underline transition-colors"
                           itemprop="item">
                            <span itemprop="name">{{ $item['name'] }}</span>
                        </a>
                        <meta itemprop="position" content="{{ $index + 1 }}">
                    @else
                        <span class="text-gray-200 font-semibold" itemprop="name">{{ $item['name'] }}</span>
                        <meta itemprop="position" content="{{ $index + 1 }}">
                        <link itemprop="item" href="{{ url()->current() }}">
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>

{{-- BreadcrumbList Schema JSON-LD --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    @foreach ($items as $index => $item)
    {
      "@@type": "ListItem",
      "position": {{ $index + 1 }},
      "name": "{{ $item['name'] }}",
      "item": "{{ $item['url'] ?? url()->current() }}"
    }{{ $index < count($items) - 1 ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
