<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTablePeriodeKeuanganNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periode_keuangan', function (Blueprint $table) {
            $table->date('tanggal_awal')->nullable()->change();
            $table->date('tanggal_akhir')->nullable()->change();
            $table->string('status_aktif')->default('Y')->nullable()->change();
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
        Schema::table('periode_keuangan', function (Blueprint $table) {
            //
        });
    }
}
