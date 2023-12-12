<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUrutanSurplusDefisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('surplus_defisit', 'urutan')) {
            Schema::table('surplus_defisit', function (Blueprint $table) {
                $table->unsignedInteger('urutan');
                $table->string('urutan_romawi',10);
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
        Schema::table('surplus_defisit', function (Blueprint $table) {
            $table->dropColumn(['urutan','urutan_romawi']);
        });
    }
}
