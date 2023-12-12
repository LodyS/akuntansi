<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingSurplusDefisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_surplus_defisit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 255)->nullable();
            $table->integer('induk')->nullable()->unsigned();
            $table->string('kode', 255)->nullable();
            $table->integer('level')->nullable()->unsigned();
            $table->integer('urutan')->nullable()->unsigned();
            $table->integer('jenis')->nullable()->signed();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_surplus_defisit');
    }
}
