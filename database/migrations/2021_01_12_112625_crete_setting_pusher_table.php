<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreteSettingPusherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_pusher', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pusher_app_id', 200)->nullable();
            $table->string('pusher_app_key', 100)->nullable();
            $table->string('pusher_app_secret', 100)->nullable();
            $table->string('pusher_app_cluster', 100)->nullable();
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
        //
    }
}
