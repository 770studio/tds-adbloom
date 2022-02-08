<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RedirectStatusesSeeder::class);
        $this->call(OpportunitiesTableSeeder::class);
        $this->call(PartnersTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(TaggablesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(WidgetOpportunityTableSeeder::class);
        $this->call(WidgetsTableSeeder::class);
        //$this->call(ConversionsHourlyStatsTableSeeder::class);
        $this->call(SchlesingerSurveysTableSeeder::class);
        $this->call(SchlesingerSurveyQualificationQuestionsTableSeeder::class);
        $this->call(SchlesingerSurveyQualificationAnswersTableSeeder::class);
        $this->call(SchlesingerSurveyQualificationsTableSeeder::class);
    }
}
