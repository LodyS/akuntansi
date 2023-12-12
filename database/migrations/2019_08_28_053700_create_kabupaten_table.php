<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKabupatenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kabupaten', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode',100)->nullable();
            $table->string('kabupaten',100)->nullable();
            $table->unsignedInteger('id_provinsi');
            $table->string('flag_aktif',1)->nullable()->default('Y');
            $table->timestamps();

            $table->foreign('id_provinsi')->references('id')->on('provinsi')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kabupaten');
    }
}
