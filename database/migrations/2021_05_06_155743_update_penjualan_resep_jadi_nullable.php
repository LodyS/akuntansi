<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePenjualanResepJadiNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan_resep', function (Blueprint $table) {
            $table->integer('id_penjualan')->unsigned()->nullable()->change();
            $table->integer('id_visit')->unsigned()->nullable()->change();
            $table->integer('id_dokter')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan_resep', function (Blueprint $table) {
            //
        });
    }
}
