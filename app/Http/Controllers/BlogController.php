<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Traits\HasSeo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    use HasSeo;

    private function getCategories()
    {
        return Cache::remember('blog_categories', 3600, function () {
            return BlogPost::published()
                ->whereNotNull('category')
                ->select('category')
                ->distinct()
                ->pluck('category');
        });
    }

    /**
     * Mostrar el índice del blog con todos los posts publicados
     */
    public function index(Request $request)
    {
        $this->setSeoData(
            'Blog | Noticias y Novedades de la Radio Dominicana',
            'Noticias, tutoriales y artículos sobre radio dominicana. Mantente informado.',
            asset('img/domiradios-logo-og.jpg')
        );

        $posts = BlogPost::published()
            ->with('user')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $featuredPosts = BlogPost::published()
            ->featured()
            ->with('user')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        $categories = $this->getCategories();

        return view('blog.index', compact('posts', 'featuredPosts', 'categories'));
    }

    /**
     * Mostrar un post individual por su slug
     */
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->published()
            ->with('user')
            ->firstOrFail();

        // Incrementar vistas
        $post->incrementViews();

        // Configurar SEO específico del post
        $this->setSeoData(
            $post->meta_title ?? $post->title,
            $post->meta_description ?? $post->excerpt ?? substr(strip_tags($post->content), 0, 160),
            $post->featured_image ? asset('storage/'.$post->featured_image) : asset('img/domiradios-logo-og.jpg'),
            ['keywords' => $post->meta_keywords]
        );

        // Obtener posts relacionados
        $relatedPosts = $post->getRelatedPosts(3);

        // Generar URL canónica
        $canonical_url = $post->canonical_url;

        return view('blog.show', compact('post', 'relatedPosts', 'canonical_url'));
    }

    /**
     * Mostrar posts por categoría
     */
    public function category($category)
    {
        $this->setSeoData(
            "Blog: {$category}",
            "Artículos de {$category} sobre radio dominicana. Mantente informado con las últimas novedades.",
            asset('img/domiradios-logo-og.jpg')
        );

        $posts = BlogPost::published()
            ->byCategory($category)
            ->with('user')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = $this->getCategories();

        return view('blog.category', compact('posts', 'category', 'categories'));
    }

    /**
     * Mostrar posts por tag
     */
    public function tag($tag)
    {
        $this->setSeoData(
            "Tag: {$tag}",
            "Artículos etiquetados con '{$tag}'. Descubre contenido relacionado en el blog.",
            asset('img/domiradios-logo-og.jpg')
        );

        $posts = BlogPost::published()
            ->byTag($tag)
            ->with('user')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = $this->getCategories();

        return view('blog.tag', compact('posts', 'tag', 'categories'));
    }

    /**
     * Buscar posts en el blog
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('blog.index');
        }

        $this->setSeoData(
            "Buscar: \"{$query}\"",
            "Resultados de búsqueda para '{$query}' en el blog.",
            asset('img/domiradios-logo-og.jpg')
        );

        $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $query);
        $posts = BlogPost::published()
            ->where(function ($q) use ($escaped) {
                $q->where('title', 'like', "%{$escaped}%")
                    ->orWhere('excerpt', 'like', "%{$escaped}%")
                    ->orWhere('content', 'like', "%{$escaped}%");
            })
            ->with('user')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = $this->getCategories();

        return view('blog.search', compact('posts', 'query', 'categories'));
    }
}
