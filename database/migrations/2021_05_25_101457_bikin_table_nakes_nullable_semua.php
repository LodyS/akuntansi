<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTableNakesNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nakes', function (Blueprint $table) {
            $table->string('kode', 255)->nullable()->change();
            $table->string('nama', 255)->nullable()->change();
            $table->integer('id_spesialisasi')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nakes', function (Blueprint $table) {
            //
        });
    }
}
