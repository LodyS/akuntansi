<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_jurnal', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('id_jurnal')->unsigned();
			$table->integer('id_perkiraan')->unsigned();
			$table->smallInteger('ref')->nullable()->unsigned();
			$table->float('debet', 20,2)->nullable();
			$table->float('kredit', 20,2)->nullable();
            $table->timestamps();
			$table->softDeletes('delete_at', 0);
			
			$table->foreign('id_perkiraan')->references('id')->on('perkiraan');
			$table->foreign('id_jurnal')->references('id')->on('jurnal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_jurnal');
    }
}
