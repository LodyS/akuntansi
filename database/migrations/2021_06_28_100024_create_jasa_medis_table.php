<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJasaMedisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jasa_medis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pegawai')->nullable()->unsigned();
            $table->string('nama_pegawai', 255)->nullable();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->float('nominal', 30,2)->nullable();
            $table->string('nama_rek',255)->nullable();
            $table->integer('id_bank')->nullable()->unsigned();
            $table->string('flag_terposting',1)->nullable();
            $table->timestamp('tanggal_posting')->nullable();
            $table->timestamps();

            $table->foreign('id_pegawai')->references('id')->on('pegawai');
            $table->foreign('id_unit')->references('id')->on('unit');
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
        Schema::dropIfExists('jasa_medis');
    }
}
