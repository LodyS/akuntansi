<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTableSubJenisUsahaNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_jenis_usaha', function (Blueprint $table) {
            $table->string('kode', 255)->nullable()->change();
            $table->string('nama', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_jenis_usaha', function (Blueprint $table) {
            //
        });
    }
}
