<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTarifNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarif', function (Blueprint $table) {
            $table->float('jasa_sarana', 30,2)->nullable()->change();
            $table->float('bhp', 30,2)->nullable()->change();
            $table->float('total_utama', 30,2)->nullable()->change();
            $table->float('persen_nakes_utama', 30,2)->nullable()->change();
            $table->float('persen_rs_utama', 30,2)->nullable()->change();
            $table->float('total_pendamping', 30,2)->nullable()->change();
            $table->float('persen_nakes_pendamping', 30,2)->nullable()->change();
            $table->float('persen_rs_pendamping', 30,2)->nullable()->change();
            $table->float('total_pendukung', 30,2)->nullable()->change();
            $table->float('persen_nakes_pendukung', 30,2)->nullable()->change();
            $table->float('persen_rs_pendukung', 30,2)->nullable()->change();
            $table->float('alkes', 30,2)->nullable()->change();
            $table->float('kr', 30,2)->nullable()->change();
            $table->float('ulup', 30,2)->nullable()->change();
            $table->float('adm', 30,2)->nullable()->change();
            $table->float('total', 30,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarif', function (Blueprint $table) {
            //
        });
    }
}
