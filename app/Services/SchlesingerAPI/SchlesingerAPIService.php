<?php

namespace App\Services\SchlesingerAPI;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * https://developer-beta.market-cube.com/api-details#api=supply-api-v2&operation=get-api-v2-survey-allocated-surveys&definition=SampleCube.SupplyAPI.Core.Models.APIResult
 */
class SchlesingerAPIService
{
    private PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::withOptions(
            ['debug' => true]
        )->withHeaders([
            'X-MC-SUPPLY-KEY' => config('services.schlesinger.survey_api.secret'),
        ]);
    }


    public function getSurveys(): SchlesingerSurveyListResponse
    {
        return new SchlesingerSurveyListResponse(
            $this->api
                ->get(config('services.schlesinger.survey_api.survey_list_url'))
                ->object()
        );

    }

    public function getIndustries()
    {

        return new SchlesingerIndustryListResponse(
            $this->api
                ->get(config('services.schlesinger.survey_api.industry_list_url'))
                ->json()
        );

    }

    public function getQualificationsByLangID(int $languageId): SchlesingerQualificationsListResponse
    {

        return new SchlesingerQualificationsListResponse(
            $this->api
                ->get(Str::replace(
                    "{languageId}"
                    , $languageId
                    , config('services.schlesinger.survey_api.qualification-answers_list_url'))
                )->object()
        );
    }

    public function getQualificationAnswersBySurvey(int $SurveyId): SchlesingerSurveyListResponse
    {
        return new SchlesingerSurveyListResponse(
            $this->api
                ->get(Str::replace(
                    "{surveyId}"
                    , $SurveyId
                    , config('services.schlesinger.survey_api.survey-qualification_list_url'))
                )->object()
        );
    }
}
