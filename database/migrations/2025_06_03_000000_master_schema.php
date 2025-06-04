<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // USERS, PASSWORD RESET, SESSIONS
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name', 100);
            $table->string('user_avatar', 255)->default('');
            $table->string('password', 100);
            $table->string('email', 120)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedInteger('user_status')->default(1);
            $table->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // CACHE
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // JOBS
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // SETTINGS
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('app_email');
            $table->string('app_copyright');
            $table->string('app_phone');
            $table->string('app_website');
            $table->string('app_facebook');
            $table->string('app_twitter');
            $table->text('app_term_of_use');
            $table->text('app_privacy_policy');
            $table->timestamps();
        });
        // THEMES
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img');
            $table->string('grad_start_color')->default('0');
            $table->string('grad_end_color')->default('0');
            $table->integer('grad_orientation')->default(0);
            $table->boolean('is_single_theme')->default(0);
            $table->boolean('isActive')->default(1);
            $table->timestamps();
        });
        // CONFIGS
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->integer('is_full_bg')->default(0);
            $table->integer('ui_top_chart')->default(1);
            $table->integer('ui_genre')->default(1);
            $table->integer('ui_favorite')->default(1);
            $table->integer('ui_themes')->default(2);
            $table->integer('ui_detail_genre')->default(1);
            $table->integer('ui_player')->default(1);
            $table->integer('ui_search')->default(1);
            $table->integer('app_type')->default(1);
            $table->timestamps();
        });

        // GENRES
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img');
            $table->boolean('isActive')->default(1);
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // RADIOS
        Schema::create('radios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('tags');
            $table->string('bitrate');
            $table->string('img');
            $table->string('type_radio');
            $table->string('source_radio');
            $table->string('link_radio');
            $table->string('user_agent_radio');
            $table->string('url_facebook');
            $table->string('url_twitter');
            $table->string('url_instagram');
            $table->string('url_website');
            $table->boolean('isFeatured')->default(0);
            $table->boolean('isActive')->default(1);
            $table->string('address')->nullable();
            $table->integer('rating')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_stream_active')->default(true);
            $table->timestamp('last_stream_check')->nullable();
            $table->timestamp('last_stream_failure')->nullable();
            $table->integer('stream_failure_count')->default(0);
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 180)->nullable();
            $table->string('og_title', 70)->nullable();
            $table->string('og_description', 180)->nullable();
            $table->string('og_image')->nullable();
            $table->string('h1', 80)->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('seo_checksum', 40)->nullable()->index();
            $table->timestamp('seo_last_checked_at')->nullable();
            $table->string('puerto_opus')->nullable();
            $table->timestamps();
        });

        // RADIO SEO BACKUPS
        Schema::create('radio_seo_backups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('radio_id');
            $table->json('snapshot');
            $table->timestamps();
        });

        // RADIOS_CAT
        Schema::create('radios_cat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_id');
            $table->unsignedBigInteger('genre_id');
            $table->timestamps();
            $table->foreign('radio_id')->references('id')->on('radios')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
        });

        // VISITAS
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_id');
            $table->timestamps();
            $table->foreign('radio_id')->references('id')->on('radios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radios_cat');
        Schema::dropIfExists('radio_seo_backups');
        Schema::dropIfExists('visitas');
        Schema::dropIfExists('genres');
        Schema::dropIfExists('radios');
        Schema::dropIfExists('users');
    }
};
