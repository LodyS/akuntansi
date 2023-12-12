<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_pembayaran')->nullable()->unsigned();
            $table->integer('no_kunjungan')->nullable()->unsigned();
            $table->string('jenis', 50);
            $table->bigInteger('total_pembayaran')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_pembayaran')->references('id')->on('pembayaran');
            $table->foreign('no_kunjungan')->references('id')->on('visit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pembayaran');
    }
}
