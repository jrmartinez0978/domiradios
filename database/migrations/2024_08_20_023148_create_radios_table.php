<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRadiosTable extends Migration
{
    public function up()
    {
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
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('radios');
    }
}
