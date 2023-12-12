<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdRekeningJadiIdPerkiraanAnggaranProfitByRekening extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('anggaran_profit_by_rekening', 'id_rekening'))
        {
            Schema::table('anggaran_profit_by_rekening', function (Blueprint $table) {
                $table->renameColumn('id_rekening', 'id_perkiraan');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggaran_profit_by_rekening', function (Blueprint $table) {
            //
        });
    }
}
