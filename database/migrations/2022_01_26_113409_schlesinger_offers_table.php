<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SchlesingerOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('schlesinger_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('SurveyId')->unique();
            $table->integer('LanguageId');
            $table->integer('BillingEntityId')->nullable();
            $table->decimal('CPI');
            $table->integer('LOI');
            $table->decimal('IR');
            $table->integer('IndustryId');
            $table->integer('StudyTypeId');
            $table->boolean('IsMobileAllowed')->default(0);
            $table->boolean('IsNonMobileAllowed')->default(0);
            $table->boolean('IsTabletAllowed')->default(0);
            $table->boolean('IsSurveyGroupExist')->default(0);
            $table->boolean('CollectPII')->default(0);
            $table->integer('AccountId');
            $table->integer('UrlTypeId');
            $table->dateTime('UpdateTimeStamp');
            $table->boolean('IsManualInc')->default(0);
            $table->boolean('IsQuotaLevelCPI')->default(0);
            $table->string('LiveLink');
            $table->dateTime('Qual_UpdateTimeStamp');
            $table->dateTime('Quota_UpdateTimeStamp');
            $table->dateTime('Group_UpdateTimeStamp');
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
        Schema::dropIfExists('schlesinger_surveys');

    }
}
