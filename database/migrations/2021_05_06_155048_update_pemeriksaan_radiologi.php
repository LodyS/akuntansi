<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePemeriksaanRadiologi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemeriksaan_radiologi', function (Blueprint $table) {
            $table->integer('id_golongan_radiologi')->nullable()->unsigned()->change();
            $table->integer('id_jenis_radiologi')->nullable()->unsigned()->change();
            $table->integer('id_layanan')->nullabel()->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pemeriksaan_radiologi', function (Blueprint $table) {
            //
        });
    }
}
