<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggaranProfitKelompokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggaran_profit_kelompok', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_anggaran_profit')->nullable()->unsigned();
            $table->string('nama',255)->nullable();
            $table->timestamps();

            $table->foreign('id_anggaran_profit')->references('id')->on('anggaran_profit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggaran_profit_kelompok');
    }
}
