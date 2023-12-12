<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->integer('id_kelompok_bisnis')->nullable()->unsigned();
            $table->integer('id_jenis_usaha')->nullable()->unsigned();
            $table->integer('id_sub_jenis_usaha')->nullable()->unsigned();
            $table->integer('id_sub_unit_usaha')->nullable()->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();

            $table->foreign('id_kelompok_bisnis')->references('id')->on('kelompok_bisnis');
            $table->foreign('id_jenis_usaha')->references('id')->on('jenis_usaha');
            $table->foreign('id_sub_jenis_usaha')->references('id')->on('sub_jenis_usaha');
            $table->foreign('id_sub_unit_usaha')->references('id')->on('sub_unit_usaha');
            $table->foreign('id_unit')->references('id')->on('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            //
        });
    }
}
