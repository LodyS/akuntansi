<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBukuBesarPembantuHutang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buku_besar_pembantu_hutang', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal');
            $table->integer('id_instansi_relasi')->nullable()->unsigned();
            $table->integer('id_periode')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->string('keterangan', 500);
            $table->float('debet', 20,2);
            $table->float('kredit', 20,2);
            $table->integer('user_input')->unsigned();
            $table->timestamps();
            $table->softDeletes('delete_at', 0);

            $table->foreign('id_instansi_relasi')->references('id')->on('instansi_relasi');
            $table->foreign('user_input')->references('id')->on('users');
            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            $table->foreign('id_periode')->references('id')->on('periode_keuangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buku_besar_pembantu_hutang');
    }
}
