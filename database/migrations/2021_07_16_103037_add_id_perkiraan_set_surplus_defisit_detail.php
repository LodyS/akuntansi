<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdPerkiraanSetSurplusDefisitDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('set_surplus_defisit_detail', function (Blueprint $table) {
            $table->integer('id_perkiraan')->nullable()->unsigned();
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
        Schema::table('set_surplus_defisit_detail', function (Blueprint $table) {
            //
        });
    }
}
