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
        Schema::table('radios', function (Blueprint $table) {
            // Indexes para columnas frecuentemente consultadas
            $table->index('slug'); // Para consultas por URL
            $table->index('source_radio'); // Para filtrar por origen
            $table->index('isActive'); // Para filtrar activas/inactivas
            $table->index('isFeatured'); // Para featured
            $table->index('rating'); // Para ordenar por rating
            $table->index('created_at'); // Para ordenar por fecha

            // Composite index para consultas comunes
            $table->index(['isActive', 'isFeatured']); // Para homepage
            $table->index(['source_radio', 'isActive']); // Para filtros
        });

        Schema::table('genres', function (Blueprint $table) {
            $table->index('slug'); // Para consultas por ciudad
            $table->index('name'); // Para búsquedas
        });

        Schema::table('radios_cat', function (Blueprint $table) {
            $table->index('radio_id'); // Para joins
            $table->index('genre_id'); // Para joins
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radios', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['source_radio']);
            $table->dropIndex(['isActive']);
            $table->dropIndex(['isFeatured']);
            $table->dropIndex(['rating']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['isActive', 'isFeatured']);
            $table->dropIndex(['source_radio', 'isActive']);
        });

        Schema::table('genres', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['name']);
        });

        Schema::table('radios_cat', function (Blueprint $table) {
            $table->dropIndex(['radio_id']);
            $table->dropIndex(['genre_id']);
        });
    }
};
