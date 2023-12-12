<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDetailPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_pembelian', function (Blueprint $table) {

            $table->integer('id_barang')->after('id_pembelian')->unsigned();
            $table->integer('id_packing_barang')->after('id_barang')->unsigned();
            $table->integer('id_perkiraan')->nullable()->after('id_packing_barang')->unsigned();

            $table->foreign('id_barang')->references('id')->on('barang');
            $table->foreign('id_packing_barang')->references('id')->on('packing_barang');
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
        Schema::table('detail_pembelian', function (Blueprint $table) {
            //
        });
    }
}
