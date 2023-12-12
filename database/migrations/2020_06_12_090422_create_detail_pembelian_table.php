<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_pembelian')->unsigned();
            $table->bigInteger('harga_pembelian')->unsigned();
            $table->integer('diskon')->unsigned();
            $table->integer('jumlah_pembelian')->unsigned();
            $table->timestamps();

            $table->foreign('id_pembelian')->references('id')->on('pembelian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pembelian');
    }
}
