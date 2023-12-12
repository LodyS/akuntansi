<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->bigInteger('total_tagihan')->default('0')->nullable()->signed()->change();    
            $table->bigInteger('jumlah_bayar')->default('0')->nullable()->signed()->change();
            $table->bigInteger('sisa_tagihan')->default('0')->nullable()->signed()->change();
            $table->bigInteger('klaim_bpjs')->default('0')->nullable()->signed()->change();
            $table->bigInteger('diskon')->default('0')->nullable()->signed()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            //
        });
    }
}
