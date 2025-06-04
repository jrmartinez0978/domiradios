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
        // Primero verificamos si la tabla existe para evitar errores
        if (Schema::hasTable('radio_seo_backups')) {
            // Verificamos si la columna no existe ya
            if (!Schema::hasColumn('radio_seo_backups', 'created_by')) {
                Schema::table('radio_seo_backups', function (Blueprint $table) {
                    $table->string('created_by')->nullable()->after('snapshot')
                          ->comment('Identificador de quien creó el respaldo (API, admin, etc)');
                });
            }
        } else {
            // Si la tabla no existe, la creamos completamente
            Schema::create('radio_seo_backups', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('radio_id');
                $table->json('snapshot');
                $table->string('created_by')->nullable()
                      ->comment('Identificador de quien creó el respaldo (API, admin, etc)');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('radio_seo_backups') && Schema::hasColumn('radio_seo_backups', 'created_by')) {
            Schema::table('radio_seo_backups', function (Blueprint $table) {
                $table->dropColumn('created_by');
            });
        }
    }
};
