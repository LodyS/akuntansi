<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemeriksaanRadiologiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemeriksaan_radiologi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_golongan_radiologi')->unsigned();
            $table->integer('id_jenis_radiologi')->unsigned();
            $table->integer('id_layanan')->unsigned();
            $table->timestamps();

            $table->foreign('id_golongan_radiologi')->references('id')->on('golongan_radiologi');
            $table->foreign('id_jenis_radiologi')->references('id')->on('jenis_radiologi');
            $table->foreign('id_layanan')->references('id')->on('layanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemeriksaan_radiologi');
    }
}
