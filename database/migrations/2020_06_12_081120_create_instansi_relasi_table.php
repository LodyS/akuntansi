<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstansiRelasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instansi_relasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 255);
            $table->string('nama', 255);
            $table->string('alamat', 255);
            $table->string('telp', 255);
            $table->string('email', 255);
            $table->string('rekening', 255);
            $table->string('atas_nama', 255);
            $table->bigInteger('saldo_hutang')->unsigned();
            $table->bigInteger('batas_kredit')->unsigned();
            $table->date('tanggal_hutang')->nullable();
            $table->date('jatuh_tempo')->nullable();
            $table->integer('id_jenis_instansi_relasi')->nullable()->unsigned();
            $table->integer('id_termin')->nullable()->unsigned();
            $table->integer('id_tarif_pajak')->nullable()->unsigned();
            $table->integer('id_provinsi')->nullable()->unsigned();
            $table->integer('id_kabupaten')->nullable()->unsigned();
            $table->integer('id_kecamatan')->nullable()->unsigned();
            $table->integer('id_kelurahan')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_jenis_instansi_relasi')->references('id')->on('jenis_instansi_relasi');
            $table->foreign('id_tarif_pajak')->references('id')->on('tarif_pajak');
            $table->foreign('id_termin')->references('id')->on('termin_pembayaran');
            $table->foreign('id_provinsi')->references('id')->on('provinsi');
            $table->foreign('id_kabupaten')->references('id')->on('kabupaten');
            $table->foreign('id_kecamatan')->references('id')->on('kecamatan');
            $table->foreign('id_kelurahan')->references('id')->on('kelurahan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instansi_relasi');
    }
}
