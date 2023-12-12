<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTableBukuBesarPembantuNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buku_besar_pembantu', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->change();
            $table->integer('user_input')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buku_besar_pembantu', function (Blueprint $table) {
            //
        });
    }
}
