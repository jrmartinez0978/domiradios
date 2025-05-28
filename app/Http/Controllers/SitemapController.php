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
        return $sitemap->toResponse(request());
    }
}
