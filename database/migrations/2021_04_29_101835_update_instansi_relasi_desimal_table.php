<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInstansiRelasiDesimalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instansi_relasi', function (Blueprint $table) {
            $table->decimal('batas_kredit', 30,2)->default('0')->nullable()->change()->unsigned();
            $table->decimal('saldo_hutang', 30,2)->default('0')->nullable()->change()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instansi_relasi', function (Blueprint $table) {
            //
        });
    }
}
