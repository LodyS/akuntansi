<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingCoaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_coa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keterangan', 255)->nullable();
            $table->string('jenis', 255)->nullable();
            $table->integer('id_bank')->unsigned()->nullable();
            $table->integer('id_kelompok_aktiva')->unsigned()->nullable();
            $table->integer('id_tarif')->unsigned()->nullable();
            $table->string('type_obat', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('type_bayar', 50)->nullable();
            $table->integer('tipe_pasien')->nullable()->unsigned();
            $table->integer('id_kelas')->unsigned()->nullable();
            $table->integer('id_perkiraan')->unsigned()->nullable();
            $table->integer('user_input')->unsigned()->nullable();
            $table->integer('user_update')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes('delete_at', 0);

            $table->foreign('id_kelompok_aktiva')->references('id')->on('kelompok_aktiva');
            $table->foreign('id_tarif')->references('id')->on('tarif');
            $table->foreign('id_bank')->references('id')->on('kas_bank');
            $table->foreign('id_kelas')->references('id')->on('kelas');
            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            $table->foreign('user_input')->references('id')->on('users');
            $table->foreign('user_update')->references('id')->on('users');
			$table->foreign('tipe_pasien')->references('id')->on('tipe_pasien');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_coa');
    }
}
