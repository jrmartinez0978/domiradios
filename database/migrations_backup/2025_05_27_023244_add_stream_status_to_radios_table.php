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
            $table->boolean('is_stream_active')->default(true);
            $table->timestamp('last_stream_check')->nullable();
            $table->timestamp('last_stream_failure')->nullable();
            $table->integer('stream_failure_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radios', function (Blueprint $table) {
            $table->dropColumn([
                'is_stream_active',
                'last_stream_check',
                'last_stream_failure',
                'stream_failure_count'
            ]);
        });
    }
};
