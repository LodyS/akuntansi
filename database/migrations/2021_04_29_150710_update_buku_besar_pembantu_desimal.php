<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBukuBesarPembantuDesimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buku_besar_pembantu', function (Blueprint $table) {
            $table->decimal('debet', 30,2)->default('0')->nullable()->change();
            $table->decimal('kredit', 30,2)->default('0')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buku_besar_pembantu', function (Blueprint $table) {
            //
        });
    }
}
