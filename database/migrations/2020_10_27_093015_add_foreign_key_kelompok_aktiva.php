<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyKelompokAktiva extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelompok_aktiva', function (Blueprint $table) {
            $table->integer('harga_perolehan')->after('nama')->nullable()->unsigned();
            $table->integer('biaya_penyusutan')->after('harga_perolehan')->nullable()->unsigned();
            $table->integer('akumulasi_penyusutan')->after('biaya_penyusutan')->nullable()->unsigned();

            $table->foreign('harga_perolehan')->references('id')->on('perkiraan');
            $table->foreign('biaya_penyusutan')->references('id')->on('perkiraan');
            $table->foreign('akumulasi_penyusutan')->references('id')->on('perkiraan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelompok_aktiva', function (Blueprint $table) {
            //
        });
    }
}
