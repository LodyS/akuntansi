<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsListKodeSurplusDefisitDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('surplus_defisit_detail', 'list_code_rekening')) {
            Schema::table('surplus_defisit_detail', function (Blueprint $table) {
                $table->string('list_code_rekening',255);
                $table->string('list_code_unit',255);
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
            $table->dropColumn(['list_code_rekening','list_code_unit']);
        });
    }
}
