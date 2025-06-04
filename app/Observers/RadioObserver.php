<?php

namespace App\Observers;

use App\Models\Radio;
use Illuminate\Support\Str;

class RadioObserver
{
    /**
     * Handle the Radio "saving" event.
     *
     * @param  \App\Models\Radio  $radio
     * @return void
     */
    public function saving(Radio $radio): void
    {
        // Generar slug si está vacío
        if (empty($radio->slug)) {
            $radio->slug = Str::slug($radio->name);
        }

        // Calcular y asignar SEO score si los campos relevantes han cambiado
        // o si el seo_score es nulo.
        if ($radio->isDirty('meta_title') || 
            $radio->isDirty('meta_description') || 
            $radio->isDirty('og_title') || 
            $radio->isDirty('og_description') || 
            $radio->isDirty('h1') || 
            is_null($radio->seo_score)) 
        {
            $radio->seo_score = $radio->calculateSeoScore();
        }
    }

    /**
     * Handle the Radio "saved" event.
     *
     * @param  \App\Models\Radio  $radio
     * @return void
     */
    public function saved(Radio $radio): void
    {
        // Si hay cambios SEO, limpiar cachés específicas que puedan contener
        // información SEO desactualizada
        if ($radio->wasChanged('meta_title') || 
            $radio->wasChanged('meta_description') || 
            $radio->wasChanged('og_title') || 
            $radio->wasChanged('og_description') || 
            $radio->wasChanged('h1') ||
            $radio->wasChanged('canonical_url')) 
        {
            // Si está disponible el sistema de caché con tags
            if (method_exists(\Illuminate\Support\Facades\Cache::class, 'tags')) {
                \Illuminate\Support\Facades\Cache::tags(['radios', 'seo'])->flush();
            }
            
            // Limpiar caché específica de esta radio
            \Illuminate\Support\Facades\Cache::forget('radio_seo_data_' . $radio->id);
            \Illuminate\Support\Facades\Cache::forget('radio_meta_' . $radio->slug);
        }
    }
}
