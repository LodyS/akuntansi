<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->nullable()->unsigned();
            $table->integer('id_perkiraan')->unsigned()->nullable();
            $table->date('tanggal')->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->float('debet', 30,2)->default('0')->nullable();
            $table->float('kredit', 30,2)->default('0')->nullable();
            $table->integer('id_periode')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('cabang_user');
            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            $table->foreign('id_periode')->references('id')->on('periode_keuangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
