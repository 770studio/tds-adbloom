<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchlesingerQualificationsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schlesinger_survey_qualification_answers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('qualification_internalId');
            $table->integer('answerId');
            $table->string('text');
            $table->string('answerCode');

            $table->unique(['qualification_internalId', 'answerId'], 'unique_answer');
            $table->foreign('qualification_internalId', 'qId_foreign')->references('id')->on('schlesinger_survey_qualification_questions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schlesinger_survey_qualification_answers');
    }
}
