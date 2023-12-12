<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutasiKasDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_kas_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_mutasi_kas')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->float('nominal', 30,2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_mutasi_kas')->references('id')->on('mutasi_kas');
            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            $table->foreign('id_unit')->references('id')->on('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_kas_detail');
    }
}
