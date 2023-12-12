<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingPerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_perusahaan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 200)->nullable();
            $table->string('nama', 200)->nullable();
            $table->string('alamat', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('telepon', 100)->nullable();
            $table->string('fax', 100)->nullable();
            $table->timestamp('tanggal_berdiri')->nullable();
            $table->string('flag_aktif')->default('Y')->nullable();
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
        Schema::dropIfExists('setting_perusahaan');
    }
}
