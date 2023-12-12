<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutasiKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_kas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 255);
            $table->integer('id_arus_kas')->nullable()->unsigned();
            $table->date('tanggal')->nullable();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('id_kas_bank')->nullable()->unsigned();
            $table->integer('id_pendapatan_jasa')->nullable()->unsigned();
            $table->bigInteger('id_pembayaran')->nullable()->unsigned();
            $table->integer('id_penjualan')->nullable()->unsigned();
            $table->integer('id_deposit')->nullable()->unsigned();
            $table->float('nominal', 30,2)->default('0')->nullable()->unsigned();
            $table->integer('tipe')->nullable()->unsigned();
            $table->text('keterangan')->nullable();
            $table->integer('user_input')->unsigned();
            $table->integer('user_update')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes('delete_at', 0);
            $table->string('ref',1)->nullable();
            $table->integer('no_jurnal')->nullable()->unsigned();

            $table->foreign('id_arus_kas')->references('id')->on('arus_kas');
            $table->foreign('id_kas_bank')->references('id')->on('kas_bank');
            $table->foreign('user_input')->references('id')->on('users');
            $table->foreign('user_update')->references('id')->on('users');
            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            $table->foreign('id_pendapatan_jasa')->references('id')->on('pendapatan_jasa');
            $table->foreign('id_pembayaran')->references('id')->on('pembayaran');
            $table->foreign('id_penjualan')->references('id')->on('penjualan');
            $table->foreign('no_jurnal')->references('id')->on('jurnal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_kas');
    }
}
