<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingAplikasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_aplikasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 200)->nullable();
            $table->string('deskripsi', 200)->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('base_url', 100)->nullable();
            $table->string('flag_morbis', 100)->nullable();
            $table->string('version', 100)->nullable();
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
        Schema::dropIfExists('setting_aplikasi');
    }
}
