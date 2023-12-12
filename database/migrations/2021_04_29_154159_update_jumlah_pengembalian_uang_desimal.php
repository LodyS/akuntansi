<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateJumlahPengembalianUangDesimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengembalian_uang', function (Blueprint $table) {
            $table->decimal('jumlah_deposit', 30,2)->default('0')->nullable()->change();
            $table->decimal('jumlah_penggunaan', 30,2)->default('0')->nullable()->change();
            $table->decimal('jumlah_pengembalian', 30,2)->default('0')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengembalian_uang', function (Blueprint $table) {
            //
        });
    }
}
