<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingCoaJasaPegawaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_coa_jasa_pegawai', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_unit')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('id_jasa_pegawai')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_unit')->references('id')->on('unit');
            $table->foreign('id_jasa_pegawai')->references('id')->on('jasa_pegawai');
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
        Schema::dropIfExists('setting_coa_jasa_pegawai');
    }
}
