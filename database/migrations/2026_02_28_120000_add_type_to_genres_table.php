<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar columna type
        Schema::table('genres', function (Blueprint $table) {
            $table->string('type', 10)->default('genre')->after('name')->index();
        });

        // 2. Marcar ciudades existentes
        DB::table('genres')
            ->whereIn('name', ['Santo Domingo', 'Azua', 'Online', 'Santiago'])
            ->update(['type' => 'city']);

        // 3. Crear géneros musicales conocidos y asociar radios por tags
        // Solo géneros de whitelist para evitar inyecciones de spam en tags
        $validGenres = [
            'Merengue', 'Bachata', 'Salsa', 'Reggaetón', 'Urbano', 'Dembow',
            'Baladas', 'Rock', 'Pop', 'Cristiana', 'Noticias', 'Deportes',
            'Tropical', 'Jazz', 'Clásica', 'Variada', 'Urbana', 'Boleros',
            'latin pop', 'soft rock', 'Deporte', 'Informativa',
            'música cristiana', 'música tropical', 'Románticas',
            'Música Latina', 'Música Mundial', 'Pop Latino', 'Top 40',
            'R&B en vivo', 'Hip Hop', 'Romantico',
        ];

        foreach ($validGenres as $genreName) {
            $slug = Str::slug($genreName);
            if (! DB::table('genres')->where('slug', $slug)->exists()) {
                DB::table('genres')->insert([
                    'name' => $genreName,
                    'slug' => $slug,
                    'type' => 'genre',
                    'img' => '',
                    'isActive' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Asociar radios a géneros basándose en tags (solo matches con whitelist)
        $radios = DB::table('radios')->whereNotNull('tags')->where('tags', '!=', '')->get();
        $genresBySlug = DB::table('genres')->where('type', 'genre')->get()->keyBy('slug');

        foreach ($radios as $radio) {
            $tags = array_map('trim', explode(',', $radio->tags));

            foreach ($tags as $tag) {
                $slug = Str::slug($tag);
                if (empty($slug) || ! isset($genresBySlug[$slug])) {
                    continue;
                }

                $genreId = $genresBySlug[$slug]->id;
                $exists = DB::table('radios_cat')
                    ->where('radio_id', $radio->id)
                    ->where('genre_id', $genreId)
                    ->exists();

                if (! $exists) {
                    DB::table('radios_cat')->insert([
                        'radio_id' => $radio->id,
                        'genre_id' => $genreId,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Eliminar géneros creados desde tags (los que no son ciudades y fueron auto-generados)
        // No eliminamos las asociaciones pivot para no perder datos de ciudades

        Schema::table('genres', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }
};
