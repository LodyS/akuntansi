<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_invoice')->unsigned()->nullable();
            $table->integer('id_item')->unsigned()->nullable();
            $table->string('keterangan',225)->nullable();
            $table->bigInteger('harga')->nullable();
            $table->bigInteger('total')->nullable();
            $table->integer('user_input')->nullable();
            $table->integer('user_update')->nullable();
            $table->integer('user_delete')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('id_invoice')->references('id')->on('invoice');
            $table->foreign('id_item')->references('id')->on('item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_invoice');
    }
}
