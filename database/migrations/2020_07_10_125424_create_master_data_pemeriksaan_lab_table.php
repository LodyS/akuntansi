<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterDataPemeriksaanLabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_data_pemeriksaan_lab', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_layanan')->unsigned();
            $table->timestamps();

            $table->foreign('id_layanan')->references('id')->on('layanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_data_pemeriksaan_lab');
    }
}
