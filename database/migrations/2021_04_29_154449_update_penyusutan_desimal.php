<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePenyusutanDesimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penyusutan', function (Blueprint $table) {
            $table->decimal('nominal', 30,2)->default('0')->nullable()->change();
            $table->decimal('nilai_buku', 30,2)->default('0')->nullable()->change();
            $table->integer('id_aktiva_tetap')->unsigned()->nullable()->change();
            $table->date('tanggal_penyusutan')->nullable()->change();
            $table->integer('user_input')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penyusutan', function (Blueprint $table) {
            //
        });
    }
}
