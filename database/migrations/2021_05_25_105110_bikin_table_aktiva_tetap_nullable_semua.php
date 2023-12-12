<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTableAktivaTetapNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aktiva_tetap', function (Blueprint $table) {
            $table->string('kode', 255)->nullable()->change();
            $table->string('nama', 255)->nullable()->change();
            $table->integer('penyusutan')->unsigned()->nullable()->change();
            $table->string('lokasi', 255)->nullable()->change();
            $table->integer('no_seri')->unsigned()->nullable()->change();
            $table->integer('umur_ekonomis')->unsigned()->nullable()->change();
            $table->integer('depreciated')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aktiva_tetap', function (Blueprint $table) {
            //
        });
    }
}
