<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKasBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas_bank', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_bank', 255)->nullable();
            $table->string('nama', 255)->nullable();
            $table->integer('id_jenis_usaha')->nullable()->unsigned();
            $table->string('rekening', 255)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->integer('kode_pos')->unsigned();
            $table->string('negara', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->bigInteger('telepon')->unsigned();
            $table->string('fax', 255)->nullable();
            $table->integer('id_user')->nullable()->unsigned();
            $table->string('flag_aktif', 1)->default('Y');
            $table->timestamps();

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
        Schema::dropIfExists('kas_bank');
    }
}
