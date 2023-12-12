d<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20);
            $table->integer('coa_diskon')->nullable()->unsigned();
            $table->integer('coa_pajak')->nullable()->unsigned();
            $table->string('jenis', 225);
            $table->string('jenis_pasien',20)->nullable();
            $table->integer('id_kelas')->nullable()->unsigned();
            $table->integer('id_produk_asuransi')->nullable()->unsigned();
            $table->string('jenis_pembayaran', 20);
            $table->integer('id_bank')->nullable()->unsigned();
            $table->timestamp('waktu');
            $table->float('pajak',20,2)->nullable();
            $table->float('total_penjualan',20,2);
            $table->float('diskon',20,2);
            $table->float('pembulatan',20,2)->unsigned();
            $table->float('total_tagihan', 20,2);
            $table->string('ref',1);
            $table->integer('no_jurnal')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
}
