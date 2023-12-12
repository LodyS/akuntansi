<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_payroll', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_referal')->nullable();
            $table->string('komponen')->nullable();
            $table->float('nominal',30,2)->nullable();
            $table->timestamps();

            $table->foreign('kode_referal')->references('kode_referal')->on('payroll');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_payroll');
    }
}
