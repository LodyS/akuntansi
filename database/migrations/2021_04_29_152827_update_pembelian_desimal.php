<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePembelianDesimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->date('waktu')->nullable()->change();
            $table->integer('id_instansi_relasi')->nullable()->unsigned()->change();
            $table->decimal('ppn', 30,2)->default('0')->nullable()->change();
            $table->decimal('diskon', 30,2)->default('0')->nullable()->change();
            $table->decimal('materai', 30,2)->default('0')->nullable()->change();
            $table->decimal('charge', 30,2)->default('0')->nullable()->change();
            $table->date('jatuh_tempo')->nullable()->change();
            $table->decimal('jumlah_nominal', 30,2)->default('0')->nullable()->change();
            $table->decimal('jumlah_tagihan', 30,2)->default('0')->nullable()->change();
            $table->integer('user_input')->nullable()->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembelian', function (Blueprint $table) {
            //
        });
    }
}
