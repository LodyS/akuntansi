<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPendapatanJasaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pendapatan_jasa', function (Blueprint $table) {
            $table->float('deposit', 20,2)->nullable()->after('no_jurnal');
            $table->float('charge', 20,2)->nullable()->after('deposit');
            $table->float('adm',20,2)->nullable()->after('charge');
            $table->float('materai', 20,2)->nullable()->after('adm');
            $table->float('biaya_kirim',20,2)->nullable()->after('materai');
            $table->integer('id_jurnal')->nullable()->unsigned()->after('no_jurnal');

            $table->foreign('id_jurnal')->references('id')->on('jurnal');
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
            $table->dropForeign(['id_jurnal']);
            $table->dropColumn(['deposit','charge','adm','materai','biaya_kirim','id_jurnal']);
        });
    }
}
