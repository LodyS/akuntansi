<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignSetNeracaRumus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('set_neraca_rumus', function (Blueprint $table) {
            $table->foreign('id_set_neraca')->references('id')->on('set_neraca');
            $table->foreign('id_rumus')->references('id')->on('set_neraca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('set_neraca_rumus', function (Blueprint $table) {
            //
        });
    }
}
