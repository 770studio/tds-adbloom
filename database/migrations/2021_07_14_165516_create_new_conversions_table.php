<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->string('Stat_tune_event_id', 50)->unique()->comment('UNIQUE ID');
            $table->string('Affiliate_company', 100)->nullable();
            $table->string('Advertiser_company', 100)->nullable();
            $table->string('ConversionsMobile_adv_sub2', 100)->nullable();
            $table->string('ConversionsMobile_adv_sub3', 100)->nullable();
            $table->string('ConversionsMobile_adv_sub4', 100)->nullable();
            $table->string('ConversionsMobile_adv_sub5', 100)->nullable();
            $table->string('ConversionsMobile_affiliate_click_id', 100)->nullable();
            $table->string('ConversionsMobile_affiliate_unique1', 100)->nullable();
            $table->string('ConversionsMobile_affiliate_unique2', 100)->nullable();
            $table->string('ConversionsMobile_affiliate_unique3', 100)->nullable();
            $table->string('ConversionsMobile_affiliate_unique4', 100)->nullable();
            $table->string('ConversionsMobile_affiliate_unique5', 100)->nullable();
            $table->string('Goal_name', 100)->nullable();
            $table->string('Offer_name', 100)->nullable();
            $table->string('Stat_advertiser_id', 100)->nullable();
            $table->string('Stat_affiliate_id', 100)->nullable();
            $table->string('Stat_affiliate_info1', 100)->nullable();
            $table->string('Stat_affiliate_info2', 100)->nullable();
            $table->string('Stat_affiliate_info3', 100)->nullable();
            $table->string('Stat_affiliate_info4', 100)->nullable();
            $table->string('Stat_affiliate_info5', 100)->nullable();
            $table->string('Stat_currency', 10)->nullable();
            $table->string('Stat_goal_id')->nullable();
            $table->string('Stat_payout', 50)->nullable();
            $table->string('Stat_revenue', 50)->nullable();
            $table->string('Stat_date')->nullable();
            $table->string('Stat_datetime')->nullable();
            $table->string('Stat_id', 50)->nullable();
            $table->string('Stat_offer_id', 50)->nullable();
            $table->string('Stat_source', 50)->nullable();
            $table->string('Stat_status', 50)->nullable();
            $table->tinyInteger('partner_postbacks')->default(0);
            $table->dateTime('partner_postback_lastsent')->nullable();

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
        Schema::dropIfExists('conversions');
    }
}
