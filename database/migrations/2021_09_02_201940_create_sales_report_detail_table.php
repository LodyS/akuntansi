<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReportDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sales_report_detail')) {
            Schema::create('sales_report_detail', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_sales_report')->nullable()->unsigned();
                $table->date('tanggal')->nullable();
                $table->float('billed', 30,2)->nullable()->unsigned();
                $table->float('persentase_billed',30,2)->nullable()->unsigned();
                $table->float('dispute')->nullable()->unsigned();
                $table->float('persentase_dispute',30,2)->nullable()->unsigned();
                $table->timestamps();

                $table->foreign('id_sales_report')->references('id')->on('sales_report');
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
        Schema::dropIfExists('sales_report_detail');
    }
}
