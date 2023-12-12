<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignIdBankInfoPembayaranInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('info_pembayaran_invoice', function (Blueprint $table) {
            $table->integer('id_bank')->unsigned()->nullable()->change();
            $table->foreign('id_bank')->references('id')->on('kas_bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('info_pembayaran_invoice', function (Blueprint $table) {
            //
        });
    }
}
