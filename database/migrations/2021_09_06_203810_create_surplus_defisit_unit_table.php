<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurplusDefisitUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('surplus_defisit_unit')) {
            Schema::create('surplus_defisit_unit', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('id_surplus_defisit_detail');
                $table->unsignedInteger('id_unit');
                $table->timestamps();

                $table->foreign('id_surplus_defisit_detail')->references('id')->on('surplus_defisit_detail');
                $table->foreign('id_unit')->references('id')->on('unit');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surplus_defisit_unit');
    }
}
