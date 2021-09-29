<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConversionsHourlyStat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('conversions_hourly_stats', function (Blueprint $table) {
            $table->id();
            $table->string('Offer_name', 100)->nullable();
            //$table->string('Stat_tune_event_id', 50)->unique()->comment('UNIQUE ID');
            $table->string('Affiliate_company', 100)->nullable();
            $table->string('OfferUrl_name', 100)->nullable();
            $table->unsignedBigInteger('Stat_affiliate_id');
            $table->unsignedBigInteger('Stat_offer_id');
            $table->unsignedBigInteger('Stat_goal_id');
            $table->unsignedBigInteger('Stat_offer_url_id');
            $table->unsignedInteger('Stat_clicks')->default(0);
            $table->unsignedInteger('Stat_conversions')->default(0);
            $table->decimal('Stat_payout', 15, 4, true)->default(0.00);
            $table->decimal('Stat_revenue', 15, 4, true)->default(0.00);
            $table->decimal('Stat_profit', 15, 4, true)->default(0.00);
            $table->string('Goal_name', 100)->nullable();
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
        Schema::dropIfExists('conversions_hourly_stats');

    }
}
