<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSchemaOrg
{
    /**
     * Generate RadioStation schema for detail page
     */
    public function getRadioStationSchema(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'RadioStation',
            'name' => $this->name,
            'url' => route('emisoras.show', $this->slug),
            'logo' => $this->optimized_logo_url,
            'image' => $this->optimized_logo_url,
            'description' => "Escucha {$this->name} en vivo. Emisora de radio {$this->bitrate} - " . Str::of($this->tags)->explode(',')->first() . ". Transmisión online desde República Dominicana.",
            'genre' => $this->tags,
            'frequency' => $this->bitrate,
            'inLanguage' => 'es',
        ];

        // Address
        if (! empty($this->address)) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->address,
                'addressCountry' => 'DO',
                'addressRegion' => $this->genres->pluck('name')->first() ?? 'Santo Domingo',
            ];
        }

        // Social links
        $sameAs = array_values(array_filter([
            $this->url_website, $this->url_facebook, $this->url_twitter, $this->url_instagram,
        ]));
        if (count($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        // Rating
        if (! empty($this->rating)) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $this->rating,
                'bestRating' => '5',
                'worstRating' => '1',
                'ratingCount' => (string) ($this->rating_count ?? 1),
            ];
        }

        // Audio + Broadcast Service
        if (! empty($this->link_radio)) {
            $schema['audio'] = [
                '@type' => 'AudioObject',
                'contentUrl' => $this->link_radio,
                'encodingFormat' => $this->type_radio ?? 'audio/mpeg',
            ];
            $schema['broadcastService'] = [
                '@type' => 'RadioBroadcastService',
                'broadcastDisplayName' => $this->name,
                'broadcastFrequency' => [
                    '@type' => 'BroadcastFrequencySpecification',
                    'broadcastFrequencyValue' => $this->bitrate,
                ],
            ];
        }

        // Content location fallback
        if (empty($this->address) && $this->genres->count()) {
            $schema['contentLocation'] = [
                '@type' => 'Place',
                'name' => $this->genres->pluck('name')->implode(', ') . ', República Dominicana',
            ];
        }

        // Speakable for voice search
        $schema['speakable'] = [
            '@type' => 'SpeakableSpecification',
            'cssSelector' => ['h1', '.radio-description'],
        ];

        return $schema;
    }

    /**
     * Generate ItemList schema for homepage
     */
    public static function getRadioListSchema($radios): array
    {
        $items = [];
        foreach ($radios as $index => $radio) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'RadioStation',
                    'name' => $radio->name,
                    'url' => route('emisoras.show', $radio->slug),
                    'image' => $radio->optimized_logo_url ?? '',
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Emisoras de Radio Dominicanas',
            'numberOfItems' => count($radios),
            'itemListElement' => $items,
        ];
    }
}
