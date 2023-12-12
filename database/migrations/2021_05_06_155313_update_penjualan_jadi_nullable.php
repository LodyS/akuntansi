<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePenjualanJadiNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->string('kode', 255)->nullable()->change();
            $table->string('jenis', 255)->nullable()->change();
            $table->string('jenis_pembayaran', 255)->nullable()->change();
            $table->float('total_penjualan',20,2)->nullable()->change();
            $table->float('diskon',20,2)->nullable()->change();
            $table->float('pembulatan',20,2)->unsigned()->nullable()->change();
            $table->float('total_tagihan', 20,2)->nullable()->change();
            $table->string('ref',1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            //
        });
    }
}
