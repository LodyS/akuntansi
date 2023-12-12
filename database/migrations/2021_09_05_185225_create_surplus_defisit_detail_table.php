<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurplusDefisitDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('surplus_defisit_detail')) {
            Schema::create('surplus_defisit_detail', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_surplus_defisit')->nullable()->unsigned();
                $table->string('nama',255)->nullable();
                $table->timestamps();

                $table->foreign('id_surplus_defisit')->references('id')->on('surplus_defisit');
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
        Schema::dropIfExists('surplus_defisit_detail');
    }
}
