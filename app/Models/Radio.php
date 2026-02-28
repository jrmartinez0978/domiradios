<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManager;           // Intervention Image v3 ImageManager
use Intervention\Image\Drivers\Gd\Driver;      // Intervention Image v3 GD Driver
use Intervention\Image\Encoders\WebpEncoder;   // Intervention Image v3 WebpEncoder
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Radio extends Model
{
    use HasFactory, LogsActivity;

    /**
     * Eager load relationships by default
     */
    protected $with = ['genres'];

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
        // Campos para monitoreo de streams
        'is_stream_active',
        'stream_failure_count',
        'last_stream_check',
        'last_stream_failure',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($radio) {
            if (empty($radio->slug)) {
                $radio->slug = Str::slug($radio->name);
            }
        });
    }

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
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($imageContent);
                    $img->scale(width: 300, height: 300);

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

    public function user()
    {
        // Relación temporal - las radios no tienen user_id actualmente
        return $this->belongsTo(\App\Models\User::class)->withDefault([
            'name' => 'Sistema',
            'email' => 'sistema@domiradios.com.do'
        ]);
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
     * Activity Log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'isActive', 'isFeatured', 'rating', 'link_radio', 'source_radio', 'type_radio'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Radio {$eventName}");
    }
}

