<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\HasSeo;

class BlogController extends Controller
{
    use HasSeo;

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

        $categories = BlogPost::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

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
            $post->featured_image ? asset('storage/' . $post->featured_image) : asset('img/domiradios-logo-og.jpg'),
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

        $categories = BlogPost::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

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

        $categories = BlogPost::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

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

        $posts = BlogPost::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->with('user')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = BlogPost::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('blog.search', compact('posts', 'query', 'categories'));
    }
}
