<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggaranProfitByRekeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggaran_profit_by_rekening', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_anggaran_profit')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_anggaran_profit')->references('id')->on('anggaran_profit');
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
        Schema::dropIfExists('anggaran_profit_by_rekening');
    }
}
