<?php

use Illuminate\Database\Migrations\Migration;

class SchlesingerOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('partners', function (Blueprint $table) {
            $table->string('logo')->nullable();
        });
        #items: array:68 [
        0 => {#1462
        +"SurveyId": 1046465
        + "LanguageId": 3
        + "BillingEntityId": null
        + "CPI": 1.0
        + "LOI": 13
        + "IR": 45.0
        + "IndustryId": 30
        + "StudyTypeId": 1
        + "IsMobileAllowed": false
        + "IsNonMobileAllowed": true
        + "IsTabletAllowed": true
        + "IsSurveyGroupExist": false
        + "CollectPII": false
        + "AccountId": 1
        + "UrlTypeId": 0
        + "UpdateTimeStamp": "2021-11-09T10:39:13.7733333"
        + "IsManualInc": false
        + "IsQuotaLevelCPI": false
        + "LiveLink": "https://qa-surveys.sample-cube.com?VID=30&SID=D2D40FDB-8876-40E0-9B39-FFA7BED894CF&LID=3&vsid=[#scid#]"
        + "Qual_UpdateTimeStamp": "2020-11-16T17:20:23.267"
        + "Quota_UpdateTimeStamp": "2020-06-23T10:21:15.047"
        + "Group_UpdateTimeStamp": "2022-01-22T23:55:06.433"

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
