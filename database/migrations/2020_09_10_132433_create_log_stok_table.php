<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_stok', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_stok')->nullable()->unsigned();
            $table->timestamp('waktu');
            $table->float('stok_awal');
            $table->float('selisih');
            $table->float('stok_akhir');
            $table->integer('id_transaksi')->nullable()->unsigned();
            $table->integer('user_input')->unsigned();
            $table->integer('user_update')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_stok')->references('id')->on('stok');
            $table->foreign('id_transaksi')->references('id')->on('jenis_transaksi');
            $table->foreign('user_input')->references('id')->on('users');
            $table->foreign('user_update')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_stok');
    }
}
