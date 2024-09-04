<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
