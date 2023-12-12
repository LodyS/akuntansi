<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKolomPerkiraanRequestRsij extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perkiraan', function (Blueprint $table) {
            $table->string('kode_rekening', 255)->nullable();
            $table->string('kelompok',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perkiraan', function (Blueprint $table) {
            //
        });
    }
}
