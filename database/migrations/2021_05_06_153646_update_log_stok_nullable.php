<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLogStokNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_stok', function (Blueprint $table) {
            $table->date('waktu')->nullable()->change();
            $table->float('stok_awal')->nullable()->change();
            $table->float('selisih')->nullable()->change();
            $table->float('stok_akhir')->nullable()->change();
            $table->integer('user_input')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_stok', function (Blueprint $table) {
            //
        });
    }
}
