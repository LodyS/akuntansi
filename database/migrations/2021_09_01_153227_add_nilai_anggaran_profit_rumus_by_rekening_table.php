<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNilaiAnggaranProfitRumusByRekeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggaran_profit_by_rekening', function (Blueprint $table) {
            $table->integer('nilai')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggaran_profit_rumus_by_rekening', function (Blueprint $table) {
            //
        });
    }
}
