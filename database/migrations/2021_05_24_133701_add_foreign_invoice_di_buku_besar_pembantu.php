<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignInvoiceDiBukuBesarPembantu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buku_besar_pembantu', function (Blueprint $table) {
            $table->foreign('id_invoice')->references('id')->on('invoice');
            $table->foreign('id_pembayaran_invoice')->references('id')->on('pembayaran_invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buku_besar_pembantu_hutang', function (Blueprint $table) {
            //
        });
    }
}
