<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_termin_pembayaran')->unsigned()->nullable();
            $table->bigInteger('id_pelanggan')->unsigned()->nullable();
            $table->string('number',225)->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('payment',225)->nullable();
            $table->date('due_date')->nullable();
            $table->text('pesan')->nullable();
            $table->bigInteger('total')->nullable();
            $table->bigInteger('ppn')->nullable();
            $table->bigInteger('subtotal')->nullable();
            $table->bigInteger('pph_23')->nullable();
            $table->integer('status')->nullable()->comment('1=belum lunas, 2=lunas');
            $table->string('flag_cetak',1)->default('N')->nullable()->comment('Y=sudah cetak, N=belum cetak');
            $table->string('flag_jurnal',1)->default('N')->nullable()->comment('Y=sudah jurnal, N=belum jurnal');
            $table->integer('id_jurnal')->unsigned()->nullable();
            $table->integer('user_input')->nullable();
            $table->integer('user_update')->nullable();
            $table->integer('user_delete')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('id_termin_pembayaran')->references('id')->on('termin_pembayaran');
            $table->foreign('id_pelanggan')->references('id')->on('pelanggan');
            $table->foreign('id_jurnal')->references('id')->on('jurnal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice');
    }
}
