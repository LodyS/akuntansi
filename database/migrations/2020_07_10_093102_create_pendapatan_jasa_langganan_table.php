<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendapatanJasaLanggananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendapatan_jasa_langganan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pendapatan_jasa')->nullable()->unsigned();
            $table->integer('id_asuransi_produk')->nullable()->unsigned();
            $table->string('perusahaan', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_pendapatan_jasa')->references('id')->on('pendapatan_jasa');
            $table->foreign('id_asuransi_produk')->references('id')->on('produk_asuransi');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendapatan_jasa_langganan');
    }
}
