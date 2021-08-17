<?php

use App\Models\Infrastructure\Platform;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOpportunities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opportunities', function (Blueprint $table) {
            $table->string('call_to_action')->nullable();
            $table->string('incentive')->nullable();
            $table->json('platforms')->nullable();
            $table->json('countries')->nullable();
            $table->json('genders')->nullable();
            $table->json('targeting_params')->nullable();
            $table->tinyInteger('age_from')->nullable();
            $table->tinyInteger('age_to')->nullable();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropColumn(
                ['call_to_action', 'incentive', 'platforms' , 'countries', 'genders', 'age_from', 'age_to' , 'targeting_params']
            );
        });
    }
}
