<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePerkiraanNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perkiraan', function (Blueprint $table) {
            $table->integer('id_kategori')->unsigned()->nullable()->change();
            $table->decimal('debet', 30,2)->unsigned()->nullable()->change();
            $table->decimal('kredit', 30,2)->unsigned()->nullable()->change();
            $table->integer('level')->unsigned()->nullable()->change();
            $table->integer('id_induk')->unsigned()->nullable()->change();
            $table->integer('type')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perkiraan', function (Blueprint $table) {
            //
        });
    }
}
