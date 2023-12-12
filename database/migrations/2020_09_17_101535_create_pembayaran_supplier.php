<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaranSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_supplier', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->bigInteger('id_pembelian')->nullable()->unsigned();
            $table->string('keterangan', 255)->nullable();
            $table->date('waktu')->nullable();
            $table->bigInteger('tagihan')->nullable()->unsigned();
            $table->bigInteger('pembayaran')->nullable()->unsigned();
            $table->bigInteger('diskon')->nullable()->unsigned();
            $table->bigInteger('sisa_tagihan')->nullable()->unsigned();
            $table->integer('id_bank')->nullable()->unsigned();
            $table->string('dibayar_oleh', 255)->nullable();
            $table->integer('no_jurnal')->nullable()->unsigned();
            $table->char('ref',1)->default('N');
            $table->integer('id_user')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_pembelian')->references('id')->on('pembelian');
            $table->foreign('id_bank')->references('id')->on('kas_bank');
            $table->foreign('id_user')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_supplier');
    }
}
