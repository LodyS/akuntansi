<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurplusDefisitRekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('surplus_defisit_rek')) {
            Schema::create('surplus_defisit_rek', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_surplus_defisit_detail')->nullable()->unsigned();
                $table->integer('id_perkiraan')->nullable()->unsigned();
                $table->timestamps();

                $table->foreign('id_surplus_defisit_detail')->references('id')->on('surplus_defisit_detail');
                $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
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
        Schema::dropIfExists('surplus_defisit_rek');
    }
}
