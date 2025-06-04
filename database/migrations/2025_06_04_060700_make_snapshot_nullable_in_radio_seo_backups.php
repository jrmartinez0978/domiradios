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
        Schema::table('radio_seo_backups', function (Blueprint $table) {
            $table->json('snapshot')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radio_seo_backups', function (Blueprint $table) {
            $table->json('snapshot')->nullable(false)->change();
        });
    }
};
