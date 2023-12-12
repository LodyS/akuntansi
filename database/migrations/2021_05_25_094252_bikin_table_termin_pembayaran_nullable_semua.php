<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTableTerminPembayaranNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('termin_pembayaran', function (Blueprint $table) {
            $table->string('kode', 255)->nullable()->change();
            $table->string('termin', 255)->nullable()->change();
            $table->string('deskripsi', 255)->nullable()->change();
            $table->bigInteger('diskon')->unsigned()->nullable()->change();
            $table->bigInteger('min_pembayaran')->unsigned()->nullable()->change();
            $table->bigInteger('denda')->unsigned()->nullable()->change();
            $table->integer('jumlah_hari')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('termin_pembayaran', function (Blueprint $table) {
            //
        });
    }
}
