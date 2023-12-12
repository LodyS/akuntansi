<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerkiraanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('perkiraan', function (Blueprint $table) {
                $table->increments('id');
                $table->string('kode', 30)->nullable();
                $table->integer('id_kategori')->unsigned();
                $table->string('nama', 255)->nullable();
                $table->string('status', 255)->nullable();
                $table->string('alias', 255)->nullable();
                $table->string('multibagian', 1)->nullable()->default('0');
                $table->string('kuantitas', 1)->nullable()->default('0');
                $table->integer('fungsi')->nullable()->unsigned();
                $table->string('bagian')->nullable();
                $table->integer('an_aktivitas')->default('0')->nullable()->unsigned();
                $table->string('ukuran', 255)->nullable();
                $table->integer('tarif')->nullable()->unsigned();
                $table->bigInteger('debet')->unsigned();
                $table->bigInteger('kredit')->unsigned();
                $table->string('flag_detail', 1)->nullable();
                $table->integer('level')->unsigned();
                $table->integer('id_induk')->unsigned();
                $table->integer('type')->unsigned();
                $table->timestamps();
                $table->softDeletes('delete_at', 0);
    
                $table->foreign('id_kategori')->references('id')->on('kategori_perkiraan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perkiraan');
    }
}
