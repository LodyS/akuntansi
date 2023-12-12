<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendapatanJasaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendapatan_jasa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('keterangan')->nullable()->unsigned();
            $table->string('no_bukti_transaksi', 255);
            $table->integer('no_kunjungan')->unsigned();
            $table->date('tanggal')->nullable();
            $table->bigInteger('id_pelanggan')->unsigned();
            $table->string('jenis', 2)->nullable();
            $table->string('tipe_bayar', 20)->nullable();
            $table->integer('tipe_pasien')->unsigned();
            $table->integer('id_user')->unsigned();
            $table->float('total_tagihan', 30,2)->nullable();
            $table->float('dibayar', 30,2)->nullable();
            $table->integer('id_bank')->nullable()->unsigned();
            $table->string('discharge', 2)->default('N');
            $table->date('waktu_pulang')->nullable();
            $table->string('ref_discharge', 2)->default('N');
            $table->bigInteger('no_jurnal')->nullable()->unsigned();
            $table->integer('user_update')->nullable()->unsigned();

            $table->foreign('tipe_pasien')->references('id')->on('tipe_pasien');
            $table->foreign('id_bank')->references('id')->on('kas_bank');
            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
            $table->foreign('no_kunjungan')->references('id')->on('visit');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('user_update')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendapatan_jasa');
    }
}
