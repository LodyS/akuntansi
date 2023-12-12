<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingCoaPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_coa_payroll', function (Blueprint $table) {
            $table->increments('id');
            $table->string('komponen')->nullable();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->string('flag_aktif')->nullable();
            $table->timestamps();

            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_coa_payroll');
    }
}
