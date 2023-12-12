<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenisUsahaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_usaha', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 255);
            $table->string('nama', 255);
            $table->integer('id_kelompok_bisnis')->nullable()->unsigned();
            $table->string('flag_aktif', 1)->default('Y');
            $table->timestamps();

            $table->foreign('id_kelompok_bisnis')->references('id')->on('kelompok_bisnis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_usaha');
    }
}
