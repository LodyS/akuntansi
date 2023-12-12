<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInstansiRelasiNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instansi_relasi', function (Blueprint $table) {
            $table->string('kode', 255)->nullable()->change();
            $table->string('nama', 255)->nullable()->change();
            $table->string('alamat', 255)->nullable()->change();
            $table->string('telp', 255)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('rekening', 255)->nullable()->change();
            $table->string('atas_nama', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instansi_relasi', function (Blueprint $table) {
            //
        });
    }
}
