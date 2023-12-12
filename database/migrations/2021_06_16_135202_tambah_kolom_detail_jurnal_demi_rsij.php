<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahKolomDetailJurnalDemiRsij extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_jurnal', function (Blueprint $table) {
            $table->integer('id_unit')->nullable()->unsigned();
            $table->integer('id_nakes')->nullable()->unsigned();

            $table->foreign('id_unit')->references('id')->on('unit');
            $table->foreign('id_nakes')->references('id')->on('nakes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_jurnal', function (Blueprint $table) {
            //
        });
    }
}
