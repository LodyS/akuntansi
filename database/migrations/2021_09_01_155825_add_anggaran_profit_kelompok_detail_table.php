<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnggaranProfitKelompokDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggaran_profit_kelompok_detail', function (Blueprint $table) {
            $table->foreign('angg_profit_kelompok')->references('id')->on('anggaran_profit_kelompok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggaran_profit_kelompok_detail', function (Blueprint $table) {
            //
        });
    }
}
