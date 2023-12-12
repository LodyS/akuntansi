<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateKelompokAktivaNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelompok_aktiva', function (Blueprint $table) {
            $table->string('kode', 255)->nullable()->change();
            $table->string('nama', 255)->nullable()->change();
            $table->string('flag_penyusutan', 1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelompok_aktiva', function (Blueprint $table) {
            //
        });
    }
}
