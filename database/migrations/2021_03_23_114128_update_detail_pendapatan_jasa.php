<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDetailPendapatanJasa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_pendapatan_jasa', function (Blueprint $table) {
            $table->foreign('id_nakes_1')->references('id')->on('penduduk');
            $table->foreign('id_nakes_2')->references('id')->on('penduduk');
            $table->foreign('id_nakes_3')->references('id')->on('penduduk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_pendapatan_jasa', function (Blueprint $table) {
            //
        });
    }
}
