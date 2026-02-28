<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();

            // Contenido básico
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Media
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();

            // Categorización
            $table->string('category')->nullable();
            $table->json('tags')->nullable();

            // Autor (sin foreign key constraint por incompatibilidad de tipos en tabla users)
            $table->unsignedInteger('user_id')->nullable()->index();

            // Estado y fechas
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();

            // Métricas
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('reading_time')->nullable(); // en minutos

            // SEO avanzado
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->string('canonical_url')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices para performance
            $table->index('slug');
            $table->index('status');
            $table->index('published_at');
            $table->index('category');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
