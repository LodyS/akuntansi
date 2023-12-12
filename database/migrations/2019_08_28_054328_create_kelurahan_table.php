<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKelurahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode',100)->nullable();
            $table->string('kelurahan',100)->nullable();
            $table->unsignedInteger('id_kecamatan');
            $table->string('flag_aktif',1)->nullable()->default('Y');
            $table->string('kodepos',10)->nullable();
            $table->string('latitude',100)->nullable();
            $table->string('longitude',100)->nullable();
            $table->string('kode_bps',100)->nullable();
            $table->timestamps();

            $table->foreign('id_kecamatan')->references('id')->on('kecamatan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelurahan');
    }
}
