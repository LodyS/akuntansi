<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarifPajakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarif_pajak', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_pajak', 255)->nullable();
            $table->integer('persentase_pajak')->unsigned();
            $table->string('status_aktif',1)->default('Y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarif_pajak');
    }
}
