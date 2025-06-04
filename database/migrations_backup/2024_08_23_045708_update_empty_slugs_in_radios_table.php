<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Radio;
use Illuminate\Support\Str;

class UpdateEmptySlugsInRadiosTable extends Migration
{
    public function up()
    {
        $radios = Radio::whereNull('slug')->orWhere('slug', '')->get();

        foreach ($radios as $radio) {
            $radio->slug = Str::slug($radio->name);
            $radio->save();
        }
    }

    public function down()
    {
        // Opcionalmente, puedes revertir los cambios si es necesario
    }
}
