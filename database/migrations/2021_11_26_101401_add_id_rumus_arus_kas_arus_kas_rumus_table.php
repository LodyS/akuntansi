<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdRumusArusKasArusKasRumusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('arus_kas_rumus', function (Blueprint $table) {
            $table->integer('id_rumus_arus_kas')->unsigned()->nullable();
            $table->integer('id_transaksi_jurnal')->unsigned()->nullable();

            $table->foreign('id_rumus_arus_kas')->references('id')->on('transaksi_jurnal');
            $table->foreign('id_transaksi_jurnal')->references('id')->on('transaksi_jurnal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('arus_kas_rumus', function (Blueprint $table) {
            //
        });
    }
}
