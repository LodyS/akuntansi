<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaranInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal')->nullable();
            $table->bigInteger('id_pelanggan')->nullable()->unsigned();
            $table->integer('id_invoice')->nullable()->unsigned();
            $table->decimal('sub_total', 30,2)->default('0')->nullable();
            $table->decimal('ppn', 30,2)->default('0')->nullable();
            $table->decimal('total', 30,2)->default('0')->nullable();
            $table->decimal('pph_23', 30,2)->default('0')->nullable();
            $table->decimal('jumlah_bayar', 30,2)->default('0')->nullable();
            $table->integer('id_bank')->nullable()->unsigned();
            $table->decimal('kurang_bayar', 30,2)->default('0')->nullable();
            $table->string('flag_jurnal',1)->nullable();
            $table->integer('no_jurnal')->nullable()->unsigned();
            $table->integer('user_input')->nullable()->unsigned();
            $table->integer('user_update')->nullable()->unsigned();
            $table->integer('user_delete')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes('delete_at', 0);

            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
            $table->foreign('id_bank')->references('id')->on('kas_bank');
            $table->foreign('id_invoice')->references('id')->on('invoice');
            $table->foreign('no_jurnal')->references('id')->on('jurnal');
            $table->foreign('user_input')->references('id')->on('users');
            $table->foreign('user_update')->references('id')->on('users');
            $table->foreign('user_delete')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_invoice');
    }
}
