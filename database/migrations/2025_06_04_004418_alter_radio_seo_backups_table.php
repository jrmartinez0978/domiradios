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
        Schema::dropIfExists('radio_seo_backups');
        
        Schema::create('radio_seo_backups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_id');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('h1')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('seo_checksum', 16)->nullable();
            $table->string('backup_reason', 50)->default('manual');
            $table->string('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('radio_id')->references('id')->on('radios')->onDelete('cascade');
            $table->index(['radio_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radio_seo_backups');
    }
};
