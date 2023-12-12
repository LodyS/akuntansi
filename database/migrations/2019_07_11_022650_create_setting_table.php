<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_aplikasi',100);
            $table->text('alamat')->nullable();
            $table->string('website',100)->nullable();
            $table->string('fax',100)->nullable();
            $table->string('telepon',100)->nullable();
            $table->string('email',100)->nullable();
            $table->text('logo')->nullable();
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
