<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchlesingerSurveyQualificationsAcceptanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schlesinger_survey_qualifications_acceptance', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('survey_internalId');
            $table->unsignedBigInteger('qualification_internalId');
            $table->dateTime('UpdateTimeStamp');
            $table->json('AnswerIds');
            $table->json('AnswerCodes');

            $table->unique(['qualification_internalId', 'survey_internalId'], 'unique_survey_qualification');

            $table->foreign('survey_internalId', 'acceptance_survey')->references('id')
                ->on('schlesinger_surveys')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('qualification_internalId', 'acceptance_qualification')->references('id')
                ->on('schlesinger_survey_qualification_questions')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schlesinger_survey_qualifications_acceptance');
    }
}
