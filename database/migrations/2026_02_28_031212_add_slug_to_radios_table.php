<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('radios', 'slug')) {
            Schema::table('radios', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('radios', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
