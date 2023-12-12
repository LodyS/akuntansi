<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetLapEkuitasDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_lap_ekuitas_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_set_lap_ekuitas')->nullable()->unsigned();
            $table->integer('id_set_surplus_defisit')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_set_lap_ekuitas')->references('id')->on('set_lap_ekuitas');
            $table->foreign('id_set_surplus_defisit')->references('id')->on('setting_surplus_defisit');
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
        Schema::dropIfExists('set_lap_ekuitas_detail');
    }
}
