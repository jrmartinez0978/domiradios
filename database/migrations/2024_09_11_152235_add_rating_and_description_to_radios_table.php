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
            $table->integer('rating')->default(0); // Campo para la clasificación
            $table->text('description')->nullable(); // Campo para la descripción
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radios', function (Blueprint $table) {
            //
        });
    }
};
