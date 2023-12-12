<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateKasBankNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kas_bank', function (Blueprint $table) {
            $table->string('kode_pos', 255)->nullable()->change();
            $table->bigInteger('telepon')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kas_bank', function (Blueprint $table) {
            //
        });
    }
}
