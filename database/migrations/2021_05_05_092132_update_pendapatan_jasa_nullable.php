<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePendapatanJasaNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pendapatan_jasa', function (Blueprint $table) {
            $table->string('no_bukti_transaksi', 255)->nullable()->change();
            $table->integer('no_kunjungan')->unsigned()->nullable()->change();
            $table->bigInteger('id_pelanggan')->unsigned()->nullable()->change();
            $table->integer('tipe_pasien')->unsigned()->nullable()->change();
            $table->integer('id_user')->unsigned()->nullable()->change();
            $table->string('ref_discharge', 2)->default('N')->nullable()->change();
            $table->string('discharge', 2)->default('N')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendapatan_jasa', function (Blueprint $table) {
            //
        });
    }
}
