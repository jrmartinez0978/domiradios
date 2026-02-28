{{--
  BroadcastService Schema Component - SEO 2025 Critical

  Uso:
  <x-broadcast-schema :radio="$radio" />

  Este schema es CRÍTICO para:
  1. Google Search para radios
  2. Google Assistant / Voice Search
  3. Apple Siri
  4. Alexa Skills
--}}

@props(['radio'])

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BroadcastService",
  "@@id": "{{ route('emisoras.show', ['slug' => $radio->slug]) }}#broadcastservice",
  "name": "{{ $radio->name }}",
  "alternateName": "{{ $radio->name }} Radio",
  "description": "{{ $radio->description ?? 'Escucha ' . $radio->name . ' en vivo online. Emisora de radio de República Dominicana transmitiendo ' . ($radio->tags ? str_replace(',', ', ', $radio->tags) : 'música y entretenimiento') . ' las 24 horas.' }}",
  "url": "{{ route('emisoras.show', ['slug' => $radio->slug]) }}",
  "image": "{{ Storage::url($radio->img) }}",
  "logo": "{{ Storage::url($radio->img) }}",
  "sameAs": [
    @if($radio->url_facebook)
    "{{ $radio->url_facebook }}",
    @endif
    @if($radio->url_twitter)
    "{{ $radio->url_twitter }}",
    @endif
    @if($radio->url_instagram)
    "{{ $radio->url_instagram }}",
    @endif
    @if($radio->url_website)
    "{{ $radio->url_website }}"
    @endif
  ],
  "broadcastFrequency": "{{ $radio->bitrate }}",
  "broadcastAffiliateOf": {
    "@@type": "Organization",
    "name": "Domiradios",
    "url": "https://domiradios.com.do"
  },
  "broadcaster": {
    "@@type": "Organization",
    "name": "{{ $radio->name }}",
    @if($radio->address)
    "address": {
      "@@type": "PostalAddress",
      "addressLocality": "{{ $radio->address }}",
      "addressCountry": "DO",
      "addressRegion": "República Dominicana"
    },
    @endif
    "contactPoint": {
      "@@type": "ContactPoint",
      "contactType": "customer service",
      "url": "{{ route('emisoras.show', ['slug' => $radio->slug]) }}"
    }
  },
  "genre": "{{ $radio->tags ? str_replace(',', ', ', $radio->tags) : 'Radio' }}",
  "inLanguage": "es-DO",
  "isLiveBroadcast": true,
  "dateCreated": "{{ $radio->created_at ? $radio->created_at->toIso8601String() : now()->toIso8601String() }}",
  "dateModified": "{{ $radio->updated_at ? $radio->updated_at->toIso8601String() : now()->toIso8601String() }}",
  @if($radio->rating)
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": {{ number_format($radio->rating, 1, '.', '') }},
    "bestRating": 5,
    "worstRating": 1,
    "ratingCount": {{ $radio->rating_count ?? 1 }}
  },
  @endif
  "potentialAction": {
    "@@type": "ListenAction",
    "target": {
      "@@type": "EntryPoint",
      "urlTemplate": "{{ $radio->link_radio }}",
      "actionPlatform": [
        "http://schema.org/DesktopWebPlatform",
        "http://schema.org/MobileWebPlatform",
        "http://schema.org/IOSPlatform",
        "http://schema.org/AndroidPlatform"
      ]
    },
    "expectsAcceptanceOf": {
      "@@type": "Offer",
      "price": "0",
      "priceCurrency": "USD",
      "availability": "https://schema.org/InStock"
    }
  },
  "areaServed": {
    "@@type": "Country",
    "name": "República Dominicana",
    "identifier": "DO"
  }
}
</script>
