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
            // Verificamos si la columna aún no existe
            if (!Schema::hasColumn('radios', 'seo_score')) {
                $table->integer('seo_score')->nullable()->default(null)->comment('Puntuación SEO calculada (0-100)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radios', function (Blueprint $table) {
            if (Schema::hasColumn('radios', 'seo_score')) {
                $table->dropColumn('seo_score');
            }
        });
    }
};
