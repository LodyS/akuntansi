<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 255)->nullable();
            $table->integer('id_jurnal')->nullable()->unsigned();
            $table->integer('disetujui_oleh')->nullable()->unsigned();
            $table->integer('dibukukan_oleh')->nullable()->unsigned();
            $table->integer('diperiksa_oleh')->nullable()->unsigned();
            $table->integer('diterima_oleh')->nullable()->unsigned();
            $table->integer('disetor_oleh')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_jurnal')->references('id')->on('jurnal');
            $table->foreign('disetujui_oleh')->references('id')->on('users');
            $table->foreign('dibukukan_oleh')->references('id')->on('users');
            $table->foreign('diperiksa_oleh')->references('id')->on('users');
            $table->foreign('diterima_oleh')->references('id')->on('users');
            $table->foreign('disetor_oleh')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher');
    }
}
