<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_faktur', 255);
            $table->string('keterangan', 500);
            $table->integer('id_jenis_pembelian')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('id_bank')->nullable()->unsigned();
            $table->date('waktu');
            $table->integer('id_instansi_relasi')->unsigned();
            $table->integer('ppn')->unsigned();
            $table->bigInteger('diskon')->unsigned();
            $table->bigInteger('materai')->unsigned();
            $table->bigInteger('charge')->unsigned();
            $table->date('jatuh_tempo');
            $table->bigInteger('jumlah_nominal')->unsigned();
            $table->bigInteger('jumlah_tagihan')->unsigned();
            $table->char('status', 1)->default('2');
            $table->integer('status_bayar')->default('2')->unsigned();
            $table->integer('user_input')->unsigned();
            $table->char('ref')->default('N');
            $table->integer('no_jurnal')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('id_instansi_relasi')->references('id')->on('instansi_relasi');
            $table->foreign('user_input')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian');
    }
}
