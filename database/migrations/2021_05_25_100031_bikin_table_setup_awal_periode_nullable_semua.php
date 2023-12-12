<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTableSetupAwalPeriodeNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setup_awal_periode', function (Blueprint $table) {
            $table->date('tanggal_setup')->nullable()->change();
            $table->date('transaksi_pertama')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setup_awal_periode', function (Blueprint $table) {
            //
        });
    }
}
