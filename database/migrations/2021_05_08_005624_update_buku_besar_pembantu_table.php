<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBukuBesarPembantuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buku_besar_pembantu', function (Blueprint $table) {
            $table->integer('id_invoice')->unsigned()->nullable()->references('id')->on('invoice');
            $table->integer('id_pembayaran_invoice')->unsigned()->nullable()->references('id')->on('pembayaran_invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buku_besar_pembantu', function (Blueprint $table) {
            $table->dropColumn(['id_invoice', 'id_pembayaran_invoice']);
        });
    }
}
