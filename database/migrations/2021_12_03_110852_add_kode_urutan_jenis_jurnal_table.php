<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKodeUrutanJenisJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jenis_transaksi', function (Blueprint $table) {
            $table->integer('id_transaksi_jurnal')->nullable()->unsigned();
            $table->string('kode')->nullable();
            $table->integer('urutan')->unsigned()->nullable();
            $table->integer('level')->unsigned()->nullable();
            $table->integer('id_induk')->unsigned()->nullable();

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
        Schema::table('jenis_transaksi', function (Blueprint $table) {
            //
        });
    }
}
