<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMutasiKas extends Migration
{
    
    public function up()
    {
        Schema::table('mutasi_kas', function (Blueprint $table) {
            $table->decimal('nominal', 30,2)->default('0')->nullable()->change()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mutasi_kas', function (Blueprint $table) {
            //
        });
    }
}
