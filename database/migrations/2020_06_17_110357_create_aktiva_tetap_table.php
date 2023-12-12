<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAktivaTetapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aktiva_tetap', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->nullable()->unsigned();
            $table->string('kode', 255);
            $table->string('nama', 255);
            $table->integer('id_kelompok_aktiva')->nullable()->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->integer('penyusutan')->unsigned();
            $table->integer('id_metode_penyusutan')->nullable()->unsigned();
            $table->string('lokasi', 255);
            $table->integer('no_seri')->unsigned();
            $table->date('tanggal_pemakaian')->nullable();
            $table->date('tanggal_selesai_pakai')->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->bigInteger('nilai_residu')->nullable()->unsigned();
            $table->integer('umur_ekonomis')->unsigned();
            $table->integer('depreciated')->unsigned();
            $table->float('harga_perolehan', 30,2);
            $table->float('penyesuaian', 30,2);
            $table->float('penyusutan_berjalan', 30,2);
            $table->float('tarif', 30,2);
            $table->integer('status_penyusutan')->nullable()->unsigned();
            $table->integer('status')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_kelompok_aktiva')->references('id')->on('kelompok_aktiva');
            $table->foreign('id_unit')->references('id')->on('unit');
            $table->foreign('id_metode_penyusutan')->references('id')->on('metode_penyusutan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aktiva_tetap');
    }
}
