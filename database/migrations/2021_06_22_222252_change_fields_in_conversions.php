<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsInConversions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->dateTime("session_datetime" )->nullable()->change();

            $table->string("source" )->nullable()->change();
            $table->string("advertiser_info" )->nullable()->change();
            $table->dateTime("session_datetime" )->nullable()->change();
            $table->string("pixel_refer" )->default('')->nullable()->change();
            $table->string("ip" , 50)->default('')->nullable()->change();
            $table->string("session_ip" , 50)->default('')->nullable()->change();
            $table->string("status" , 50)->default('')->nullable()->change();
            $table->string("country_code" , 5)->nullable()->change();
            $table->string("event_city" , 50)->nullable()->change();
            $table->string("event_region" , 50)->nullable()->change();
            $table->unsignedInteger("browser_id"  )->nullable()->change();
            $table->unsignedTinyInteger("is_adjustment"  )->nullable()->change();
            $table->unsignedInteger("ad_campaign_id"  )->nullable()->change();
            $table->unsignedInteger("ad_campaign_creative_id"  )->nullable()->change();
            $table->unsignedInteger("offer_file_id"  )->nullable()->change();
            $table->string("payout_type"  )->nullable()->change();
            $table->string("revenue_type"  )->nullable()->change();
            $table->string("currency" , 10 )->nullable()->change();
            $table->string("promo_code"   )->nullable()->change();


        });
    }
}
