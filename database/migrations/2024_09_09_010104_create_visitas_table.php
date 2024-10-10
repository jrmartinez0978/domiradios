<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitasTable extends Migration
{
    public function up()
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_id');
            $table->timestamps();

            $table->foreign('radio_id')->references('id')->on('radios')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitas');
    }
}

