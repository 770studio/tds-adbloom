<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SchlesingerQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schlesinger_survey_qualification_questions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('LanguageId');
            $table->integer('qualificationId');
            $table->string('name');
            $table->string('text');
            $table->integer('qualificationCategoryId');
            $table->integer('qualificationTypeId');
            $table->integer('qualificationCategoryGDPRId');

            $table->unique(['LanguageId', 'qualificationId'], 'unique_qualification');


        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schlesinger_survey_qualification_questions', function (Blueprint $table) {
            $table->dropIfExists();
        });
    }
}
