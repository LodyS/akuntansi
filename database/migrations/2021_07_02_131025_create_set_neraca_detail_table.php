<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetNeracaDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_neraca_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('id_set_neraca')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            $table->foreign('id_set_neraca')->references('id')->on('set_neraca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('set_neraca_detail');
    }
}
