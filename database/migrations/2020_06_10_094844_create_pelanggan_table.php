<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePelangganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 255)->nullable();
            $table->string('nama', 255)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->string('flag_aktif', 1)->default('Y')->nullable();
            $table->string('telp', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->bigInteger('batas_kredit')->nullable()->unsigned();
            $table->bigInteger('saldo_piutang')->nullable()->unsigned();
            $table->date('jatuh_tempo')->nullable();
            $table->date('tanggal_piutang')->nullable();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('id_user')->nullable()->unsigned();
            $table->integer('id_termin')->nullable()->unsigned();
            $table->integer('id_provinsi')->nullable()->unsigned();
            $table->integer('id_kabupaten')->nullable()->unsigned();
            $table->integer('id_kecamatan')->nullable()->unsigned();
            $table->integer('id_kelurahan')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
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
        Schema::dropIfExists('pelanggan');
    }
}
