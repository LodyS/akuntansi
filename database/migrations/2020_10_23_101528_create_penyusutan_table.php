<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenyusutanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penyusutan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_aktiva_tetap')->unsigned();
            $table->date('tanggal_penyusutan');
            $table->integer('urutan_penyusutan')->unsigned();
            $table->float('nominal', 20,2);
            $table->float('nilai_buku', 20,2);
            $table->integer('user_input')->unsigned();
            $table->integer('user_update')->nullable()->unsigned();
            $table->integer('user_delete')->nullable()->unsigned();
            $table->string('ref',1)->default('N');
            $table->integer('no_jurnal')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);

            $table->foreign('id_aktiva_tetap')->references('id')->on('aktiva_tetap');
            $table->foreign('user_input')->references('id')->on('users');
            $table->foreign('user_update')->references('id')->on('users');
            $table->foreign('user_delete')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penyusutan');
    }
}
