<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTabelTagihanNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagihan', function (Blueprint $table) {
            $table->integer('tanggal')->unsigned()->nullable()->change();
            $table->string('type',2)->nullable()->change();
            $table->integer('no_kunjungan')->unsigned()->nullable()->change();
            $table->bigInteger('id_pelanggan')->unsigned()->nullable()->change();
            $table->integer('id_user')->unsigned()->nullable()->change();
            $table->integer('no_jurnal')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagihan', function (Blueprint $table) {
            //
        });
    }
}
