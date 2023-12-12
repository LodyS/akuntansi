<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKecamatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode',100)->nullable();
            $table->string('kecamatan',100)->nullable();
            $table->unsignedInteger('id_kabupaten');
            $table->string('flag_aktif',1)->nullable()->default('Y');
            $table->timestamps();

            $table->foreign('id_kabupaten')->references('id')->on('kabupaten')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kecamatan');
    }
}
