<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBukuBesarPembantuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buku_besar_pembantu', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal');
            $table->bigInteger('id_pelanggan')->nullable()->unsigned();
            $table->integer('id_periode')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('id_pendapatan_jasa')->nullable()->unsigned();
            $table->bigInteger('id_pembayaran')->nullable()->unsigned();
            $table->string('keterangan', 255)->nullable();
            $table->bigInteger('debet')->unsigned();
            $table->bigInteger('kredit')->unsigned();
            $table->integer('user_input')->unsigned();
            
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);

            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
            $table->foreign('id_periode')->references('id')->on('periode_keuangan');
            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            $table->foreign('user_input')->references('id')->on('users');
            $table->foreign('id_pendapatan_jasa')->references('id')->on('pendapatan_jasa');
            $table->foreign('id_pembayaran')->references('id')->on('pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buku_besar_pembantu');
    }
}
