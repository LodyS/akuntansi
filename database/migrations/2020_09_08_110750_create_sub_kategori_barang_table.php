<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubKategoriBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_kategori_barang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 255);
            $table->integer('id_kategori_barang')->nullable()->unsigned();
            $table->string('permintaan_penjualan', 255);
            $table->integer('user_input')->unsigned();
            $table->timestamps();

            $table->foreign('id_kategori_barang')->references('id')->on('kategori_barang');
            $table->foreign('user_input')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_kategori_barang');
    }
}
