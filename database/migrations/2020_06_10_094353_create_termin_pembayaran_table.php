<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerminPembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('termin_pembayaran', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 255);
            $table->string('termin', 255);
            $table->string('deskripsi', 255);
            $table->bigInteger('diskon')->unsigned();
            $table->bigInteger('min_pembayaran')->unsigned();
            $table->bigInteger('denda')->unsigned();
            $table->integer('jumlah_hari')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('termin_pembayaran');
    }
}
