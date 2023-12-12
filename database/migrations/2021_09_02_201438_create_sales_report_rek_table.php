<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReportRekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sales_report_rek')) {
            Schema::create('sales_report_rek', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_sales_report')->nullable()->unsigned();
                $table->integer('id_perkiraan')->nullable()->unsigned();
                $table->timestamps();

                $table->foreign('id_sales_report')->references('id')->on('sales_report');
                $table->foreign('id_perkiraan')->references('id')->on('perkiraan');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_report_rek');
    }
}
