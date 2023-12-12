<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 255);
            $table->string('nama_badan_usaha', 50);
            $table->string('nama_unit_usaha', 50);
			$table->string('kode_unit_usaha', 6);
			$table->string('alamat_perusahaan', 100);
			$table->string('kota', 50);
			$table->string('negara_perusahaan', 50);
			$table->string('kode_pos', 50);
			$table->string('telepon_perusahaan');
			$table->string('fax_perusahaan', 50);
            $table->string('email_perusahaan', 50);
            $table->string('npwp', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perusahaan');
    }
}
