<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_bkm', 255);
            $table->bigInteger('id_pelanggan')->unsigned();
            $table->integer('id_tagihan')->nullable()->unsigned();
            $table->integer('no_kunjungan')->unsigned();
            $table->bigInteger('total_tagihan')->default('0')->signed();    
            $table->bigInteger('jumlah_bayar')->default('0')->signed();
            $table->bigInteger('sisa_tagihan')->default('0')->signed();
            $table->bigInteger('klaim_bpjs')->default('0')->signed();
            $table->bigInteger('diskon')->default('0')->signed();
            $table->timestamp('waktu')->nullable();
            $table->string('flag_batal', 2)->default('N');
            $table->integer('id_bank')->unsigned();
            $table->string('ref', 2)->default('N');
            $table->integer('no_jurnal')->unsigned();
            $table->integer('user_update')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
            $table->foreign('id_tagihan')->references('id')->on('tagihan');
            $table->foreign('no_kunjungan')->references('id')->on('visit');
            $table->foreign('user_update')->references('id')->on('users');
            $table->foreign('id_bank')->references('id')->on('kas_bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
}
