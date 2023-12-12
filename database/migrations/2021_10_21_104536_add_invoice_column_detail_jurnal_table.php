<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceColumnDetailJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('detail_jurnal', 'invoice')) {
            Schema::table('detail_jurnal', function (Blueprint $table) {
                $table->string('invoice', 255)->nullable();
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
        Schema::table('detail_jurnal', function (Blueprint $table) {
            $table->dropColumn(['invoice']);
        });
    }
}
