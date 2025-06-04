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
            $table->string('meta_title')->nullable()->after('radio_id');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('og_title')->nullable()->after('meta_description');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->string('h1')->nullable()->after('og_image');
            $table->string('canonical_url')->nullable()->after('h1');
            $table->string('seo_checksum')->nullable()->after('canonical_url');
            $table->string('backup_reason')->nullable()->after('snapshot')->comment('Razón del respaldo (manual, automático, etc)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radio_seo_backups', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'og_title',
                'og_description',
                'og_image',
                'h1',
                'canonical_url',
                'seo_checksum',
                'backup_reason'
            ]);
        });
    }
};
