<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_trackings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('survey_id');
            $table->string('user_id');
            $table->string('publisher_id');
            $table->string('user_agent');
            $table->string('user_ip');
            $table->string('user_referer');
            $table->dateTime('landing_time');
            $table->integer('final_status')->default(0);
            $table->dateTime('final_update_time');
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
        Schema::dropIfExists('survey_trackings');
    }
}
