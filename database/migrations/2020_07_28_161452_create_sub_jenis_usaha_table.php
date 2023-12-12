<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubJenisUsahaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_jenis_usaha', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 255);
            $table->string('nama', 255);
            $table->integer('id_jenis_usaha')->nullable()->unsigned();
            $table->string('flag_aktif', 1)->default('Y');
            $table->timestamps();

            $table->foreign('id_jenis_usaha')->references('id')->on('jenis_usaha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_jenis_usaha');
    }
}
