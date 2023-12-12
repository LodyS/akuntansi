<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagJurnalDetailPayroll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_payroll', function (Blueprint $table) {
            $table->string('flag_journal')->nullable()->default('N');
            $table->integer('id_jurnal')->nullable()->unsigned();

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
        Schema::table('detail_payroll', function (Blueprint $table) {
            //
        });
    }
}
