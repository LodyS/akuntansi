<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArusKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arus_kas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 255);
            $table->integer('tipe')->nullable()->unsigned();
            $table->integer('level')->nullable()->unsigned();
            $table->integer('urutan')->nullable()->unsigned();
            $table->integer('id_induk')->nullable()->unsigned();
            $table->integer('jenis')->nullable()->unsigned();
            $table->integer('user_input')->unsigned();
            $table->integer('user_update')->nullable()->unsigned();
            $table->integer('user_delete')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes('delete_at', 0);

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
        Schema::dropIfExists('arus_kas');
    }
}
