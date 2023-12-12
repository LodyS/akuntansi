<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdPerkiraanTarifPajakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarif_pajak', function (Blueprint $table) {
            $table->integer('id_perkiraan')->nullable()->unsigned();

            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarif_pajak', function (Blueprint $table) {
            //
        });
    }
}
