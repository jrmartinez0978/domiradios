@props(['radio'])

{{-- LocalBusiness Schema - SEO 2025 Local Business Optimization --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": ["LocalBusiness", "RadioStation"],
    "@@id": "{{ route('emisoras.show', ['slug' => $radio->slug]) }}#localbusiness",
    "name": "{{ $radio->name }}",
    "alternateName": "{{ $radio->name }} {{ $radio->bitrate }}",
    "description": "{{ Str::limit(strip_tags($radio->description ?? 'Escucha ' . $radio->name . ' en vivo. Emisora de radio dominicana transmitiendo 24/7 con la mejor programación.'), 250) }}",
    "url": "{{ route('emisoras.show', ['slug' => $radio->slug]) }}",
    "logo": "{{ Storage::url($radio->img) }}",
    "image": "{{ Storage::url($radio->img) }}",

    {{-- Dirección (República Dominicana) --}}
    "address": {
        "@@type": "PostalAddress",
        "addressLocality": "{{ $radio->genres->pluck('name')->implode(', ') ?: 'Santo Domingo' }}",
        "addressRegion": "Distrito Nacional",
        "addressCountry": {
            "@@type": "Country",
            "name": "República Dominicana"
        }
    },

    {{-- Coordenadas geográficas (Centro de República Dominicana) --}}
    "geo": {
        "@@type": "GeoCoordinates",
        "latitude": "18.7357",
        "longitude": "-70.1627"
    },

    {{-- Información de contacto --}}
    @if($radio->phone)
    "telephone": "{{ $radio->phone }}",
    @endif
    @if($radio->email)
    "email": "{{ $radio->email }}",
    @endif

    {{-- Redes sociales (sameAs) --}}
    "sameAs": [
        @if($radio->facebook)
        "{{ $radio->facebook }}",
        @endif
        @if($radio->instagram)
        "{{ $radio->instagram }}",
        @endif
        @if($radio->twitter)
        "{{ $radio->twitter }}",
        @endif
        @if($radio->youtube)
        "{{ $radio->youtube }}",
        @endif
        @if($radio->website)
        "{{ $radio->website }}",
        @endif
        "{{ route('emisoras.show', ['slug' => $radio->slug]) }}"
    ],

    {{-- Horarios de operación (24/7 para radio online) --}}
    "openingHoursSpecification": [
        {
            "@@type": "OpeningHoursSpecification",
            "dayOfWeek": [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
                "Sunday"
            ],
            "opens": "00:00",
            "closes": "23:59"
        }
    ],

    {{-- Frecuencia de transmisión --}}
    "broadcastFrequency": "{{ $radio->bitrate }}",

    {{-- Calificación agregada (si existe) --}}
    @if($radio->ratings_count > 0)
    "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": {{ round($radio->ratings_avg_stars ?? 0, 1) }},
        "ratingCount": {{ $radio->ratings_count }},
        "bestRating": 5,
        "worstRating": 1
    },
    @endif

    {{-- Precio: Gratuito --}}
    "priceRange": "Gratis",

    {{-- Idioma --}}
    "inLanguage": "es-DO",

    {{-- Área de servicio --}}
    "areaServed": {
        "@@type": "Country",
        "name": "República Dominicana"
    },

    {{-- Categoría de negocio --}}
    "knowsAbout": "{{ Str::of($radio->tags)->explode(',')->implode(', ') }}",

    {{-- Servicios ofrecidos --}}
    "makesOffer": [
        {
            "@@type": "Offer",
            "itemOffered": {
                "@@type": "Service",
                "name": "Transmisión de radio en vivo online gratis",
                "description": "Escucha {{ $radio->name }} en vivo desde cualquier dispositivo sin registro ni descargas"
            },
            "price": "0",
            "priceCurrency": "USD"
        }
    ],

    {{-- Características adicionales --}}
    "hasOfferCatalog": {
        "@@type": "OfferCatalog",
        "name": "Programación de {{ $radio->name }}",
        "itemListElement": [
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "BroadcastService",
                    "name": "Transmisión en vivo 24/7",
                    "broadcastDisplayName": "{{ $radio->name }} en vivo"
                }
            }
        ]
    },

    {{-- Potencial acción: Escuchar --}}
    "potentialAction": {
        "@@type": "ListenAction",
        "target": {
            "@@type": "EntryPoint",
            "urlTemplate": "{{ route('emisoras.show', ['slug' => $radio->slug]) }}",
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
    }
}
</script>
