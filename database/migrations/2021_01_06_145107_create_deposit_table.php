<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_visit')->unsigned()->nullable();
            $table->bigInteger('id_pelanggan')->unsigned()->nullable();
            $table->timestamp('waktu')->nullable();
            $table->float('kredit', 20,2)->nullable();
            $table->float('pemakaian', 20,2)->nullable();
            $table->integer('status')->unsigned();
            $table->integer('id_induk')->unsigned()->nullable();
            $table->integer('id_pengembalian_uang')->unsigned()->nullable();
            $table->bigInteger('id_pembayaran')->nullable()->unsigned();
            $table->string('ref', 1)->default('N');
            $table->integer('id_jurnal')->nullable()->unisgned();
            $table->timestamps();

            $table->foreign('id_visit')->references('id')->on('visit');
            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
            $table->foreign('id_pembayaran')->references('id')->on('pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposit');
    }
}
