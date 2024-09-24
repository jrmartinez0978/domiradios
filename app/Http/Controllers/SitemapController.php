<?php

namespace App\Http\Controllers;

use App\Models\Radio;
use App\Models\Genre;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        // Crear una nueva instancia de Sitemap
        $sitemap = Sitemap::create();

        // Añadir la página principal
        $sitemap->add(Url::create(route('inicio'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // Añadir la página de todas las ciudades
        $sitemap->add(Url::create(route('ciudades.index'))
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8));

        // Añadir cada emisora al sitemap
        $radios = Radio::all();
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

        // Puedes añadir más URLs según las necesidades de tu aplicación...

        // Enviar el sitemap en formato XML como respuesta
        return $sitemap->toResponse(request());
    }
}

