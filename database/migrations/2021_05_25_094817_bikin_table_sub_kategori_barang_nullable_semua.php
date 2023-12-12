<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BikinTableSubKategoriBarangNullableSemua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_kategori_barang', function (Blueprint $table) {
            $table->string('nama',255)->nullable()->change();
            $table->string('permintaan_penjualan', 255)->nullable()->change();
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
        Schema::table('sub_kategori_barang', function (Blueprint $table) {
            //
        });
    }
}
