<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_packing_barang')->nullable()->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->float('hpp', 30,2)->nullable();
            $table->float('hna', 30,2)->nullable();
            $table->float('jumlah_stok', 30,2);
            $table->timestamps();

            $table->foreign('id_packing_barang')->references('id')->on('packing_barang');
            $table->foreign('id_unit')->references('id')->on('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok');
    }
}
