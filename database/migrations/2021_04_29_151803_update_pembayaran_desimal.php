<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePembayaranDesimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('total_tagihan', 30,2)->default('0')->nullable()->change();
            $table->decimal('jumlah_bayar', 30,2)->default('0')->nullable()->change();
            $table->decimal('sisa_tagihan', 30,2)->default('0')->nullable()->change();
            $table->decimal('klaim_bpjs', 30,2)->default('0')->nullable()->change();
            $table->decimal('diskon', 30,2)->default('0')->nullable()->change();
            $table->integer('id_bank')->nullable()->unsigned()->change();
            $table->decimal('deposit', 30,2)->default('0')->nullable()->change();
            $table->decimal('charge', 30,2)->default('0')->nullable()->change();
            $table->decimal('adm', 30,2)->default('0')->nullable()->change();
            $table->decimal('biaya_materai', 30,2)->default('0')->nullable()->change();
            $table->decimal('biaya_kirim', 30,2)->default('0')->nullable()->change();

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
