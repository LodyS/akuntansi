<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_penjualan')->unsigned();
            $table->integer('id_barang')->nullable()->unsigned();
            $table->float('hna', 20,2)->nullable();
            $table->float('margin', 20,2)->nullable();
            $table->float('jumlah_penjualan', 20,2)->nullable();
            $table->integer('diskon')->unsigned();
            $table->float('total', 20,2)->nullable();
            $table->integer('id_user')->unsigned();
            $table->timestamps();

            $table->foreign('id_penjualan')->references('id')->on('penjualan');
            $table->foreign('id_barang')->references('id')->on('barang');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_detail_penjualan');
    }
}
