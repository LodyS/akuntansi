<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenjualanResepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_resep', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_penjualan')->unsigned();
            $table->integer('id_visit')->unsigned();
            $table->integer('id_dokter')->unsigned();
            
            $table->foreign('id_penjualan')->references('id')->on('penjualan');
            $table->foreign('id_visit')->references('id')->on('visit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_resep');
    }
}
