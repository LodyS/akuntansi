<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggaranProfitKelompokDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggaran_profit_kelompok_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_anggaran_profit_kelompok')->nullable()->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->timestamps();

            //$table->foreign('id_anggaran_profit_kelompok')->references('id')->on('anggaran_profit_kelompok');
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
        Schema::dropIfExists('anggaran_profit_kelompok_detail');
    }
}
