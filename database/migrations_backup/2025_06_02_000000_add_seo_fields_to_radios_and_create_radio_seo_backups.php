<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // snapshots para rollback
        Schema::create('radio_seo_backups', function (Blueprint $table) {
            $table->increments('id'); // INT UNSIGNED autoincremental
            $table->unsignedInteger('radio_id'); // INT UNSIGNED, igual que radios.id
            $table->json('snapshot');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radio_seo_backups');
    }
};
