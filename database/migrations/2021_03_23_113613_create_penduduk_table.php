<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendudukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_indentitas', 255)->nullable();
            $table->string('nama', 255)->nullable();
            $table->string('jenis_kelamin', 255)->nullable();
            $table->string('gol_darah',2)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_kartu_keluarga', 255)->nullable();
            $table->string('posisi_di_keluarga', 255)->nullable();
            $table->string('sip', 255)->nullable();
            $table->string('ibu_kandung', 255)->nullable();
            $table->string('npwp', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penduduk');
    }
}
