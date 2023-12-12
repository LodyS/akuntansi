<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarif', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_kelas')->nullable()->unsigned();
            $table->integer('id_layanan')->nullable()->unsigned();
            $table->float('jasa_sarana', 30,2);
            $table->float('bhp', 30,2);
            $table->float('total_utama', 30,2);
            $table->float('persen_nakes_utama', 30,2);
            $table->float('persen_rs_utama', 30,2);
            $table->float('total_pendamping', 30,2);
            $table->float('persen_nakes_pendamping', 30,2);
            $table->float('persen_rs_pendamping', 30,2);
            $table->float('total_pendukung', 30,2);
            $table->float('persen_nakes_pendukung', 30,2);
            $table->float('persen_rs_pendukung', 30,2);
            $table->float('alkes', 30,2);
            $table->float('kr', 30,2);
            $table->float('ulup', 30,2);
            $table->float('adm', 30,2);
            $table->float('total', 30,2);
            $table->timestamps();

            $table->foreign('id_kelas')->references('id')->on('kelas');
            $table->foreign('id_layanan')->references('id')->on('layanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarif');
    }
}
