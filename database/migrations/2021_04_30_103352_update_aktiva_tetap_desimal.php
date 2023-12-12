<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAktivaTetapDesimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aktiva_tetap', function (Blueprint $table) {
            $table->decimal('nilai_residu', 30,2)->default('0')->nullable()->change();
            $table->decimal('harga_perolehan', 30,2)->default('0')->nullable()->change();
            $table->decimal('penyesuaian', 30,2)->default('0')->nullable()->change();
            $table->decimal('penyusutan_berjalan', 30,2)->default('0')->nullable()->change();
            $table->decimal('tarif', 30,2)->default('0')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
