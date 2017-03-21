<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrewSlotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crew_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mission_id');
            $table->integer('role_id');
            $table->integer('user_id')->nullable();

            $table->foreign('mission_id')->references('mission')->on('id');
            $table->foreign('role_id')->references('role')->on('id');
            $table->foreign('user_id')->references('user')->on('id');
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
        Schema::drop('crew_slots');
    }
}
