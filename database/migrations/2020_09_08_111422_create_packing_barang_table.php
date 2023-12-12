<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packing_barang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('barcode', 255);
            $table->string('satuan', 255);
            $table->integer('id_barang')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_barang')->references('id')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packing_barang');
    }
}
