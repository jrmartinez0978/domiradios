<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('radio_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radio_id')->constrained('radios')->cascadeOnDelete();
            $table->string('device_id', 64);
            $table->tinyInteger('rating')->unsigned();
            $table->timestamps();

            $table->unique(['radio_id', 'device_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radio_ratings');
    }
};
