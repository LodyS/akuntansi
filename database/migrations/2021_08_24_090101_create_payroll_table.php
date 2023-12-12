<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal_transaksi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('no_rekening')->nullable();
            $table->float('total_tagihan',30,2)->nullable();
            $table->float('biaya_adm_bank',30,2)->nullable();
            $table->float('total_uang_diterima',30,2)->nullable();
            $table->string('kode_referal')->nullable()->unique();
            $table->float('pajak',30,2)->nullable();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->string('flag_jurnal')->default('N')->nullable();
            $table->integer('id_jurnal')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_unit')->references('id')->on('unit');
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
        Schema::dropIfExists('payroll');
    }
}
