<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemesTable extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('themes');
    }
}

