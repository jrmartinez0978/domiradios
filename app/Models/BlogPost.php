<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image',
        'featured_image_alt',
        'category',
        'tags',
        'user_id',
        'status',
        'published_at',
        'views',
        'reading_time',
        'is_featured',
        'allow_comments',
        'canonical_url',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'views' => 'integer',
        'reading_time' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generar slug automáticamente si está vacío
        static::saving(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }

            // Calcular tiempo de lectura automáticamente
            if (! empty($post->content) && empty($post->reading_time)) {
                $post->reading_time = static::calculateReadingTime($post->content);
            }

            // Establecer meta_title por defecto si está vacío
            if (empty($post->meta_title)) {
                $post->meta_title = $post->title;
            }

            // Generar excerpt automáticamente si está vacío
            if (empty($post->excerpt) && ! empty($post->content)) {
                $post->excerpt = Str::limit(strip_tags($post->content), 160);
            }
        });
    }

    /**
     * Calcular tiempo de lectura en minutos
     * Promedio: 200 palabras por minuto en español
     */
    protected static function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200);

        return max(1, $minutes); // Mínimo 1 minuto
    }

    /**
     * Relación con el usuario autor
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope para posts publicados
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope para posts destacados
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para posts por categoría
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope para posts por tag
     */
    public function scopeByTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * Obtener URL de la imagen destacada optimizada
     */
    public function getOptimizedFeaturedImageUrlAttribute(): string
    {
        if (empty($this->featured_image)) {
            return asset('images/default-blog-post.jpg');
        }

        $originalPath = $this->featured_image;

        if (! Storage::disk('public')->exists($originalPath)) {
            logger()->warning('Featured image not found for blog post '.$this->id.': '.$originalPath);

            return asset('images/default-blog-post.jpg');
        }

        try {
            $imageName = pathinfo($originalPath, PATHINFO_FILENAME).'.webp';
            $optimizedDirPath = 'blog/optimized';
            $optimizedFullPath = $optimizedDirPath.'/'.$imageName;

            // Crear imagen optimizada si no existe
            if (! Storage::disk('public')->exists($optimizedFullPath) ||
                Storage::disk('public')->lastModified($originalPath) > Storage::disk('public')->lastModified($optimizedFullPath)) {
                if (! Storage::disk('public')->exists($optimizedDirPath)) {
                    Storage::disk('public')->makeDirectory($optimizedDirPath);
                }

                $imageContent = Storage::disk('public')->get($originalPath);

                // Procesar con Intervention Image v3
                $manager = new ImageManager(new Driver);
                $img = $manager->read($imageContent);
                $img->scale(width: 1200);

                $encodedImage = $img->encode(new WebpEncoder(quality: 85));
                Storage::disk('public')->put($optimizedFullPath, (string) $encodedImage);
            }

            return Storage::url($optimizedFullPath);
        } catch (\Exception $e) {
            logger()->error('Error optimizing featured image for blog post '.$this->id.' ('.$originalPath.'): '.$e->getMessage());

            return asset('images/default-blog-post.jpg');
        }
    }

    /**
     * Obtener URL canónica del post
     */
    public function getCanonicalUrlAttribute($value): string
    {
        if (! empty($value)) {
            return $value;
        }

        return route('blog.show', $this->slug);
    }

    /**
     * Incrementar contador de vistas
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Verificar si el post está publicado
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' &&
               $this->published_at !== null &&
               $this->published_at->isPast();
    }

    /**
     * Obtener fecha de publicación formateada
     */
    public function getFormattedPublishedDateAttribute(): string
    {
        if (! $this->published_at) {
            return 'No publicado';
        }

        return $this->published_at->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
    }

    /**
     * Obtener posts relacionados por tags o categoría
     */
    public function getRelatedPosts(int $limit = 3)
    {
        $query = static::published()
            ->where('id', '!=', $this->id);

        $query->where(function ($q) {
            // Priorizar posts con tags similares
            if (! empty($this->tags) && is_array($this->tags)) {
                foreach ($this->tags as $tag) {
                    $q->orWhereJsonContains('tags', $tag);
                }
            }

            // Si no hay suficientes, agregar posts de la misma categoría
            if (! empty($this->category)) {
                $q->orWhere('category', $this->category);
            }
        });

        return $query->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
