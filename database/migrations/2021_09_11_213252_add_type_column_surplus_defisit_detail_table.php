<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnSurplusDefisitDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('surplus_defisit_detail', 'type')) {
            Schema::table('surplus_defisit_detail', function (Blueprint $table) {
                $table->integer('type')->default(1)->comment('1=penambah, -1=pengurang');
                $table->tinyInteger('urutan');
                $table->tinyInteger('aktif')->default(1);
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
        Schema::table('surplus_defisit_detail', function (Blueprint $table) {
            $table->dropColumn(['type','urutan','aktif']);
        });
    }
}
