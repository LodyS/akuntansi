<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagihanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pendapatan_jasa')->nullable()->unsigned();
            $table->integer('id_detail_pendapatan_jasa')->nullable()->unsigned();
            $table->date('tanggal');
            $table->integer('no_kunjungan')->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();

            $table->float('dokter', 20,2)->nullable();
            $table->float('tindakan', 20,2)->nullable();
            $table->float('lab', 20,2)->nullable();
            $table->float('usg_ekg', 20,2)->nullable();
            $table->float('alkes', 20,2)->nullable();
            $table->float('kr', 20,2)->nullable();
            $table->float('ulup', 20,2)->nullable();
            $table->float('adm', 20,2)->nullable();
            $table->float('tarif', 20,2)->nullable();
            $table->float('obat', 20,2)->nullable();
            $table->float('piutang', 20,2)->nullable();

            $table->unsignedBigInteger('id_pelanggan')->unsigned();
            $table->string('type', 2);
            $table->char('status_tagihan', 1)->default('N');
            $table->integer('id_user')->unsigned();
            $table->char('ref', 1)->default('N');
            $table->integer('no_jurnal')->unsigned()->unsigned();

            $table->foreign('id_pendapatan_jasa')->references('id')->on('pendapatan_jasa');
            $table->foreign('id_detail_pendapatan_jasa')->references('id')->on('detail_pendapatan_jasa');
            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
            $table->foreign('id_unit')->references('id')->on('unit');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('no_kunjungan')->references('id')->on('visit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagihan');
    }
}
