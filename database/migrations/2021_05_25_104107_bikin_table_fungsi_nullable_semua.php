<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTableFungsiNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fungsi', function (Blueprint $table) {
            $table->string('nama_fungsi', 255)->nullable()->change();
            $table->string('status_aktif', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fungsi', function (Blueprint $table) {
            //
        });
    }
}
