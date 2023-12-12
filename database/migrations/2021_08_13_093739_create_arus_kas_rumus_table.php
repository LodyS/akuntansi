<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArusKasRumusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arus_kas_rumus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_arus_kas')->nullable()->unsigned();
            $table->integer('id_rumus')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_arus_kas')->references('id')->on('arus_kas');
            $table->foreign('id_rumus')->references('id')->on('arus_kas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arus_kas_rumus');
    }
}
