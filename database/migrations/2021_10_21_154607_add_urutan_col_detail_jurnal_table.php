<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrutanColDetailJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('detail_jurnal', 'layer')) {
            Schema::table('detail_jurnal', function (Blueprint $table) {
                $table->tinyInteger('layer')->nullable();
                $table->tinyInteger('urutan')->nullable();
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
            $table->dropColumn(['layer','urutan']);
        });
    }
}
