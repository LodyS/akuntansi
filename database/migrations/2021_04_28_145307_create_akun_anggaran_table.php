<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAkunAnggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akun_anggaran', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 255)->nullable();
            $table->string('nama', 255)->nullable();
            $table->integer('tipe')->nullable()->unsigned();
            $table->integer('level')->nullable()->unsigned();
            $table->integer('urutan')->nullable()->unsigned();
            $table->integer('id_induk')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('user_input')->nullable()->unsigned();
            $table->integer('user_update')->nullable()->unsigned();
            $table->integer('user_delete')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes('delete_at', 0);

            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            $table->foreign('user_input')->references('id')->on('users');
            $table->foreign('user_update')->references('id')->on('users');
            $table->foreign('user_delete')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akun_anggaran');
    }
}
