<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePerusahaanNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->string('kode', 255)->nullable()->change();
            $table->string('nama_badan_usaha', 250)->nullable()->change();
            $table->string('nama_unit_usaha', 250)->nullable()->change();
			$table->string('kode_unit_usaha', 250)->nullable()->change();
			$table->string('alamat_perusahaan', 250)->nullable()->change();
			$table->string('kota', 250)->nullable()->change();
			$table->string('negara_perusahaan', 250)->nullable()->change();
			$table->string('kode_pos', 250)->nullable()->change();
			$table->string('telepon_perusahaan')->nullable()->change();
			$table->string('fax_perusahaan', 250)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            //
        });
    }
}
