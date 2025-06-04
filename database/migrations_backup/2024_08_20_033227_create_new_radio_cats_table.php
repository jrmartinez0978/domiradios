<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('radios_cat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_id');
            $table->unsignedBigInteger('genre_id');
            $table->timestamps();

            $table->foreign('radio_id')->references('id')->on('radios')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('radios_cat');
    }
};

