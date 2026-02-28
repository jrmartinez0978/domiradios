<?php

namespace App\Http\Controllers;

use App\Models\Radio;
use App\Models\Genre;
use App\Models\BlogPost;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Sitemap Controller - SEO 2025 Optimizado
 *
 * Mejoras aplicadas:
 * - Cache de 6 horas
 * - Prioridades optimizadas
 * - Frecuencias realistas
 * - Soporte para imágenes
 */
class SitemapController extends Controller
{
    public function index()
    {
        // Cache de 6 horas (SEO 2025: Balance entre freshness y performance)
        // Cache the rendered XML string, not the Sitemap object, to avoid serialization issues
        $xml = Cache::remember('sitemap_xml_2025', 21600, function () {
            return $this->generateSitemap()->render();
        });

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Generate sitemap with SEO 2025 optimizations
     */
    private function generateSitemap()
    {
        // Crear una nueva instancia de Sitemap
        $sitemap = Sitemap::create();

        // Añadir la página principal
        $sitemap->add(Url::create(route('emisoras.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // Añadir la página de todas las ciudades
        $sitemap->add(Url::create(route('ciudades.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8));

        // Añadir cada emisora activa al sitemap
        $radios = Radio::where('isActive', true)->get(); // Filtrar por activas
        foreach ($radios as $radio) {
            $sitemap->add(Url::create(route('emisoras.show', ['slug' => $radio->slug]))
                ->setLastModificationDate($radio->updated_at ?? Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.7));
        }

        // Añadir cada ciudad al sitemap (géneros)
        $genres = Genre::all();
        foreach ($genres as $genre) {
            $sitemap->add(Url::create(route('ciudades.show', ['slug' => $genre->slug]))
                ->setLastModificationDate($genre->updated_at ?? Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.6));
        }

        // Añadir Blog - Página principal
        $sitemap->add(Url::create(route('blog.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8));

        // Añadir posts del blog
        $posts = BlogPost::published()->get();
        foreach ($posts as $post) {
            $sitemap->add(Url::create(route('blog.show', ['slug' => $post->slug]))
                ->setLastModificationDate($post->updated_at ?? $post->published_at ?? Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority($post->is_featured ? 0.7 : 0.6));
        }

        // Añadir categorías del blog
        $categories = BlogPost::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        foreach ($categories as $category) {
            $sitemap->add(Url::create(route('blog.category', ['category' => $category]))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.5));
        }

        // Añadir páginas estáticas
        $sitemap->add(Url::create(route('terminos'))
            ->setLastModificationDate(Carbon::now()) // O fecha de última modificación real si la tienes
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.3));

        $sitemap->add(Url::create(route('privacidad'))
            ->setLastModificationDate(Carbon::now()) // O fecha de última modificación real si la tienes
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.3));

        $sitemap->add(Url::create(route('contacto'))
            ->setLastModificationDate(Carbon::now()) // O fecha de última modificación real si la tienes
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5));

        // Puedes añadir más URLs según las necesidades de tu aplicación...

        // Enviar el sitemap en formato XML como respuesta
        return $sitemap;
    }

    /**
     * Generate Image Sitemap for Google Images SEO
     * SEO 2025: Crítico para visibilidad en Google Images
     */
    public function imageSitemap()
    {
        // Cache de 12 horas para image sitemap
        $xml = Cache::remember('image_sitemap_xml_2025', 43200, function () {
            return $this->generateImageSitemap();
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate image sitemap XML
     */
    private function generateImageSitemap()
    {
        $radios = Radio::where('isActive', true)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

        // Homepage con imágenes de todas las emisoras
        $xml .= '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . route('emisoras.index') . '</loc>' . PHP_EOL;
        $xml .= '    <lastmod>' . Carbon::now()->toAtomString() . '</lastmod>' . PHP_EOL;

        foreach ($radios as $radio) {
            $imageUrl = url('storage/radios/' . basename($radio->img));
            $xml .= '    <image:image>' . PHP_EOL;
            $xml .= '      <image:loc>' . htmlspecialchars($imageUrl) . '</image:loc>' . PHP_EOL;
            $xml .= '      <image:title>' . htmlspecialchars($radio->name . ' - Logo Oficial') . '</image:title>' . PHP_EOL;
            $xml .= '      <image:caption>' . htmlspecialchars('Logo de ' . $radio->name . ' ' . $radio->bitrate . ' - Radio ' . ($radio->tags ? explode(',', $radio->tags)[0] : 'Online') . ' en vivo desde República Dominicana') . '</image:caption>' . PHP_EOL;
            $xml .= '      <image:geo_location>República Dominicana</image:geo_location>' . PHP_EOL;
            $xml .= '    </image:image>' . PHP_EOL;
        }

        $xml .= '  </url>' . PHP_EOL;

        // Cada página de emisora con su imagen
        foreach ($radios as $radio) {
            $imageUrl = url('storage/radios/' . basename($radio->img));

            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . route('emisoras.show', ['slug' => $radio->slug]) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . ($radio->updated_at ?? Carbon::now())->toAtomString() . '</lastmod>' . PHP_EOL;
            $xml .= '    <image:image>' . PHP_EOL;
            $xml .= '      <image:loc>' . htmlspecialchars($imageUrl) . '</image:loc>' . PHP_EOL;
            $xml .= '      <image:title>' . htmlspecialchars($radio->name . ' ' . $radio->bitrate . ' - Escuchar en Vivo') . '</image:title>' . PHP_EOL;
            $xml .= '      <image:caption>' . htmlspecialchars('Escucha ' . $radio->name . ' en vivo online gratis. Emisora de radio ' . ($radio->tags ? explode(',', $radio->tags)[0] : 'dominicana') . ' transmitiendo 24/7 desde República Dominicana.') . '</image:caption>' . PHP_EOL;
            $xml .= '      <image:geo_location>República Dominicana</image:geo_location>' . PHP_EOL;
            $xml .= '    </image:image>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Clear sitemap cache
     * Útil cuando se agregan/modifican emisoras
     */
    public function clearCache()
    {
        Cache::forget('sitemap_xml_2025');
        Cache::forget('image_sitemap_xml_2025');

        return response()->json([
            'success' => true,
            'message' => 'All sitemap caches cleared. Next request will regenerate them.'
        ]);
    }
}
