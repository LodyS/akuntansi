<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPendapatanJasaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pendapatan_jasa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pendapatan_jasa')->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->integer('id_nakes_1')->nullable()->unsigned();
            $table->integer('id_nakes_2')->nullable()->unsigned();
            $table->integer('id_nakes_3')->nullable()->unsigned();
            $table->integer('id_tarif')->nullable()->unsigned();
            $table->float('jasa_sarana', 30,2)->nullable();
            $table->float('bhp', 30,2)->nullable();
            $table->float('jasa_medis', 20,2)->nullable();
            $table->float('jasa_rs', 20,2)->nullable();
            $table->float('tarif', 20,2)->nullable();
            $table->float('alkes', 20,2)->nullable();
            $table->float('kr', 20,2)->nullable();
            $table->float('ulup', 20,2)->nullable();
            $table->float('adm', 20,2)->nullable();
            $table->string('ref', 1)->default('N');
            $table->bigInteger('no_jurnal')->nullable()->unsigned();

            $table->foreign('id_pendapatan_jasa')->references('id')->on('pendapatan_jasa');
            $table->foreign('id_tarif')->references('id')->on('tarif');
            $table->foreign('id_unit')->references('id')->on('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pendapatan_jasa');
    }
}
