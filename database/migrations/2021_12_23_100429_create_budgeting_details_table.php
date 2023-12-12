<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgeting_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_budgeting')->nullable()->unsigned();
            $table->integer('id_perkiraan')->nullable()->unsigned();
            $table->integer('id_unit')->nullable()->unsigned();
            $table->float('nominal',30,2)->unsigned()->nullable();
            $table->string('flag_verif',1)->default('N')->nullable();
            $table->timestamps();

            $table->foreign('id_budgeting')->references('id')->on('budgeting');
            $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
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
        Schema::dropIfExists('budgeting_details');
    }
}
