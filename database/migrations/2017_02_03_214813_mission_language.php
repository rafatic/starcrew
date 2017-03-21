<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MissionLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_languages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('mission_id')->unsigned();
            $table->integer('language_id')->unsigned();

            $table->foreign('mission_id')->references('mission')->on('id');
            $table->foreign('language_id')->references('language')->on('id');

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
        Schema::drop('mission_languages');
    }
}
