<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdTarifPajakTipeMutasiKasDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mutasi_kas_detail', function (Blueprint $table) {
            $table->integer('id_tarif_pajak')->nullable()->unsigned();
            $table->string('tipe',1)->nullable();

            $table->foreign('id_tarif_pajak')->references('id')->on('tarif_pajak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mutasi_kas_detail', function (Blueprint $table) {
            //
        });
    }
}
