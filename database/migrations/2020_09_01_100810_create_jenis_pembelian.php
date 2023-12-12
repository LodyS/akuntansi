<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenisPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_pembelian', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 255);
            $table->integer('id_perkiraan_diskon')->nullable()->unsigned();
            $table->integer('id_perkiraan_pajak')->nullable()->unsigned();
            $table->integer('id_perkiraan_materai')->nullable()->unsigned();
            $table->integer('id_perkiraan_pembelian')->nullable()->unsigned();
            $table->integer('id_perkiraan_hutang')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_perkiraan_diskon')->references('id')->on('perkiraan');
            $table->foreign('id_perkiraan_pajak')->references('id')->on('perkiraan');
            $table->foreign('id_perkiraan_materai')->references('id')->on('perkiraan');
            $table->foreign('id_perkiraan_pembelian')->references('id')->on('perkiraan');
            $table->foreign('id_perkiraan_hutang')->references('id')->on('perkiraan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_pembelian');
    }
}
