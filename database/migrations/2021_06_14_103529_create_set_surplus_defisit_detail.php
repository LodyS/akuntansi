<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetSurplusDefisitDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_surplus_defisit_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_set_surplus_defisit')->nullable()->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_set_surplus_defisit')->references('id')->on('setting_surplus_defisit');
            $table->foreign('id_unit')->references('id')->on('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('set_surplus_defisit_detail');
    }
}
