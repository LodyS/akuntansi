<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalSebelumDiskonMutasiKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mutasi_kas', function (Blueprint $table) {
            $table->float('total_sebelum_diskon',30,2)->unsigned()->nullable();
            $table->float('diskon',30,2)->unsigned()->nullable();
            $table->float('total_setelah_diskon',30,2)->unsigned()->nullable();
            $table->float('ppn',30,2)->unsigned()->nullable();
            $table->float('materai',30,2)->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mutasi_kas', function (Blueprint $table) {
            //
        });
    }
}
