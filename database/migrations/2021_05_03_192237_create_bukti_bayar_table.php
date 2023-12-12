<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuktiBayarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bukti_bayar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('atas_nama',255)->nullable();
            $table->string('telp1',100)->nullable();
            $table->string('telp2',100)->nullable();
            $table->string('email',100)->nullable();
            $table->integer('user_input')->nullable();
            $table->integer('user_update')->nullable();
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
        Schema::dropIfExists('bukti_bayar');
    }
}
