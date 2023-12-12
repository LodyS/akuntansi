<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePembayaranSupplierDesimalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran_supplier', function (Blueprint $table) {
            $table->decimal('tagihan', 30,2)->default('0')->nullable()->change();
            $table->decimal('pembayaran', 30,2)->default('0')->nullable()->change();
            $table->decimal('diskon', 30,2)->default('0')->nullable()->change();
            $table->decimal('sisa_tagihan', 30,2)->default('0')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran_supplier', function (Blueprint $table) {
            //
        });
    }
}
