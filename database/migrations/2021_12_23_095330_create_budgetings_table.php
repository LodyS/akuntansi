<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgeting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama',255)->nullable();
            $table->date('tanggal_input')->nullable();
            $table->date('periode_anggaran')->nullable();
            $table->integer('user_input')->nullable()->unsigned();
            $table->string('flag_verif',2)->default('N')->nullable();
            $table->date('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_input')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgetings');
    }
}
