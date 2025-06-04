<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Laravel\Facades\Image; // Intervention Image v3 Facade
use Intervention\Image\Encoders\WebpEncoder;   // Intervention Image v3 WebpEncoder
use Illuminate\Support\Str;

class Radio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tags',
        'bitrate',
        'img', // Ruta original de la imagen
        'type_radio',
        'source_radio',
        'link_radio',
        'user_agent_radio',
        'url_facebook',
        'url_twitter',
        'url_instagram',
        'url_website',
        'isFeatured',
        'isActive',
        'rating',
        'description',
        'address',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'h1',
        'canonical_url',
        'seo_checksum',
        'seo_last_checked_at',
        'seo_score',
    ];


    /**
     * Get the URL of the optimized logo.
     *
     * @return string
     */
    public function getOptimizedLogoUrlAttribute(): string
    {
        if (empty($this->img)) {
            return asset('images/default-radio-logo.png'); // Asegúrate que esta imagen exista
        }

        $originalPath = $this->img; // e.g., 'radios_logos/imagen.jpg'

        if (!Storage::disk('public')->exists($originalPath)) {
            logger()->warning('Original image not found for radio ' . $this->id . ': ' . $originalPath);
            return asset('images/default-radio-logo.png');
        }

        $fileLastModifiedTime = Storage::disk('public')->lastModified($originalPath);
        $cacheKey = 'optimized_logo_url_' . md5($originalPath) . '_' . $fileLastModifiedTime;
        $cacheDuration = now()->addHours(24);

        return Cache::remember($cacheKey, $cacheDuration, function () use ($originalPath) {
            try {
                $imageName = pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';
                $optimizedDirPath = 'radios/optimized'; // Directorio para imágenes optimizadas
                $optimizedFullPath = $optimizedDirPath . '/' . $imageName;

                // Crear directorio si no existe y si la imagen optimizada no existe ya o es más antigua
                if (!Storage::disk('public')->exists($optimizedFullPath) || 
                    Storage::disk('public')->lastModified($originalPath) > Storage::disk('public')->lastModified($optimizedFullPath)) 
                {
                    if (!Storage::disk('public')->exists($optimizedDirPath)) {
                        Storage::disk('public')->makeDirectory($optimizedDirPath);
                    }

                    $imageContent = Storage::disk('public')->get($originalPath);
                    
                    // Procesar con Intervention Image v3
                    $img = Image::read($imageContent);
                    $img->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    
                    $encodedImage = $img->encode(new WebpEncoder(quality: 90));
                    Storage::disk('public')->put($optimizedFullPath, (string) $encodedImage);
                }

                return Storage::url($optimizedFullPath);
            } catch (\Exception $e) {
                logger()->error('Error optimizing image for radio ' . $this->id . ' (' . $originalPath . '): ' . $e->getMessage());
                return asset('images/default-radio-logo.png');
            }
        });
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'radios_cat', 'radio_id', 'genre_id');
    }

    public function seoBackups()
    {
        return $this->hasMany(\App\Models\RadioSeoBackup::class);
    }
    
    public function getDescripcionAttribute()
    {
        return $this->attributes['description'] ?? null;
    }
    
    public function setDescripcionAttribute($value)
    {
        $this->attributes['description'] = $value;
    }
    
    public function getDescriptionAttribute($value)
    {
        return $value;
    }
    
    /**
     * Calcula la puntuación SEO basada en varios factores
     * 
     * @return int
     */
    public function calculateSeoScore()
    {
        $score = 100;
        
        // Título
        $titleLength = strlen($this->meta_title ?? '');
        if ($titleLength < 45 || $titleLength > 60) $score -= 15;
        if (!str_contains(strtolower($this->meta_title ?? ''), strtolower($this->name))) $score -= 5;
        
        // Descripción
        $descLength = strlen($this->meta_description ?? '');
        if ($descLength < 130 || $descLength > 160) $score -= 15;
        if (!preg_match('/escucha|sintoniza|en vivo/i', $this->meta_description ?? '')) $score -= 5;
        
        // H1
        if (empty($this->h1)) $score -= 10;
        
        // Open Graph
        if (empty($this->og_title)) $score -= 5;
        if (empty($this->og_description)) $score -= 5;
        
        return max(0, $score);
    }
    
    /**
     * Scope para filtrar emisoras que necesitan optimización SEO
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $threshold
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsSeo($query, $threshold = 75)
    {
        return $query->where('isActive', true)
                     ->where(function ($q) use ($threshold) {
                         $q->whereNull('seo_score')
                           ->orWhere('seo_score', '<', $threshold)
                           ->orWhereNull('seo_last_checked_at')
                           ->orWhere('seo_last_checked_at', '<', now()->subDays(30));
                     });
    }
}

