<?php

namespace App\Traits;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
// use Artesaos\SEOTools\Facades\JsonLd; // Descomentar si se va a usar JSON-LD

trait HasSeo
{
    /**
     * Establece los datos SEO básicos.
     *
     * @param string $title
     * @param string $description
     * @param string|null $image URL de la imagen para OpenGraph/Twitter Cards
     * @param array $keywords
     * @param string|null $url URL explícita para OpenGraph
     * @return void
     */
    public function setSeoData($title, $description, $image = null, $keywords = [], $url = null)
    {
        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        if ($keywords) {
            SEOTools::addKeywords($keywords);
        }

        // Determine URL for OpenGraph
        $currentUrl = $url; // Use provided URL if available
        if (!$currentUrl && !app()->runningInConsole()) {
            $currentUrl = request()->fullUrl();
        } elseif (!$currentUrl && config('app.url')) {
            // Fallback for console if no specific URL is passed but APP_URL is set
            // This might need to be more specific depending on context, but it's better than request() in console
            $currentUrl = config('app.url'); 
        }

        // OpenGraph
        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        if ($currentUrl) {
            OpenGraph::setUrl($currentUrl);
        }

        $ogImage = $image;
        if (!$ogImage) {
            $defaultImageConfig = config('seotools.opengraph.defaults.images.0', '/img/domiradios-logo-og.png');
            $ogImage = str_starts_with($defaultImageConfig, 'http') ? $defaultImageConfig : asset($defaultImageConfig);
        }
        if ($ogImage) { // Ensure we have an image before adding
            OpenGraph::addImage($ogImage, ['secure_url' => str_replace('http://', 'https://', $ogImage)]);
        }
        
        OpenGraph::setSiteName(config('seotools.opengraph.defaults.site_name', config('app.name')));
        OpenGraph::setType(config('seotools.opengraph.defaults.type', 'WebSite'));

        // Twitter Card
        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        
        $twitterImage = $image;
        if (!$twitterImage) {
            $defaultTwitterImageConfig = config('seotools.twitter.defaults.images.0', '/img/domiradios-logo-og.png');
            $twitterImage = str_starts_with($defaultTwitterImageConfig, 'http') ? $defaultTwitterImageConfig : asset($defaultTwitterImageConfig);
        }
        if ($twitterImage) { // Ensure we have an image before adding
            TwitterCard::setImage($twitterImage);
        }
        TwitterCard::setSite(config('seotools.twitter.defaults.site', '@Domiradios'));

        // JSON-LD (Opcional)
        // if ($currentUrl) {
        //     JsonLd::setType('WebPage')->setUrl($currentUrl);
        // }
        // JsonLd::setTitle($title);
        // JsonLd::setDescription($description);
        // if ($ogImage) {
        //     JsonLd::addImage($ogImage);
        // }
    }
}
