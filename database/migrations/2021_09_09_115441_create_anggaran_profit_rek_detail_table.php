<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggaranProfitRekDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggaran_profit_rek_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_anggaran_profit_rek')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_anggaran_profit_rek')->references('id')->on('anggaran_profit_by_rekening');
            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggaran_profit_rek_detail');
    }
}
