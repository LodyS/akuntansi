<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDetailJurnalIdPerkiraanNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_jurnal', function (Blueprint $table) {
            $table->integer('id_jurnal')->unsigned()->nullable()->change();
            $table->integer('id_perkiraan')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_jurnal', function (Blueprint $table) {
            //
        });
    }
}
