<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HapusKolomPerkiraan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perkiraan', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('alias');
            $table->dropColumn('multibagian');
            $table->dropColumn('kuantitas');
            $table->dropColumn('an_aktivitas');
            $table->dropColumn('ukuran');
            $table->dropColumn('tarif');
            $table->dropColumn('flag_detail');
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
