<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailSyaratAnggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_syarat_anggaran', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_syarat_anggaran')->nullable()->unsigned();
            $table->string('syarat', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_syarat_anggaran')->references('id')->on('syarat_anggaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_syarat_anggaran');
    }
}
