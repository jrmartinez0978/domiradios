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
        Schema::create('radio_seo_backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radio_id')->constrained('radios')->onDelete('cascade');
            $table->json('snapshot')->comment('Datos SEO en formato JSON');
            $table->string('created_by')->nullable()->comment('Identificador de quien creó el respaldo (API, admin, etc)');
            $table->timestamps();
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
