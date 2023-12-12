<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal', function (Blueprint $table) {
            $table->increments('id');
			$table->string('kode_jurnal', 50);
			$table->date('tanggal_posting');
			$table->string('keterangan', 255)->nullable();
			$table->integer('id_tipe_jurnal')->nullable()->unsigned();
            $table->integer('id_user')->unsigned();
            $table->string('no_dokumen', 255)->nullable();
            $table->timestamps();
			$table->softDeletes('delete_at', 0);
			
			$table->foreign('id_tipe_jurnal')->references('id')->on('tipe_jurnal');
			$table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurnal');
    }
}
