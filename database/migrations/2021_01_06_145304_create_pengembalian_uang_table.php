<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengembalianUangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengembalian_uang', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('id_pelanggan')->unsigned()->nullable();
            $table->integer('id_deposit')->unsigned()->nullable();
            $table->float('jumlah_deposit', 20,2)->nullable();
            $table->float('jumlah_penggunaan', 20,2)->nullable();
            $table->float('jumlah_pengembalian', 20,2)->nullable();
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
            $table->foreign('id_deposit')->references('id')->on('deposit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengembalian_uang');
    }
}
