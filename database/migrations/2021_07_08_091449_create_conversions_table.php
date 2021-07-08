<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('offer_id');
            $table->dateTime('datetime');
            $table->date('batch_date_utc');
            $table->timestamp('batch_timestamp')->nullable();
            $table->decimal('payout', 15, 5)->default(0.00000);
            $table->decimal('revenue', 15, 5)->default(0.00000);
            $table->string('ad_id', 50);
            $table->string('tune_event_id');
            $table->unsignedInteger('advertiser_manager_id');
            $table->unsignedInteger('advertiser_id');
            $table->unsignedInteger('affiliate_manager_id');
            $table->unsignedInteger('goal_id');
            $table->unsignedInteger('creative_url_id');
            $table->unsignedInteger('customer_id');
            $table->string('source')->nullable();
            $table->text('affiliate_info1')->nullable();
            $table->text('affiliate_info2')->nullable();
            $table->text('affiliate_info3')->nullable();
            $table->text('affiliate_info4')->nullable();
            $table->text('affiliate_info5')->nullable();
            $table->text('advertiser_info')->nullable();
            $table->dateTime('session_datetime');
            $table->text('refer');
            $table->string('pixel_refer')->default('');
            $table->string('ip', 50);
            $table->string('session_ip', 50);
            $table->string('status', 50);
            $table->unsignedInteger('status_code');
            $table->decimal('sale_amount', 15, 5)->default(0.00000);
            $table->text('user_agent');
            $table->string('country_code', 5);
            $table->string('event_city', 50);
            $table->string('event_region', 50);
            $table->unsignedInteger('browser_id');
            $table->unsignedTinyInteger('is_adjustment');
            $table->unsignedInteger('ad_campaign_id');
            $table->unsignedInteger('ad_campaign_creative_id');
            $table->unsignedInteger('offer_file_id');
            $table->string('payout_type');
            $table->string('revenue_type');
            $table->string('currency', 10);
            $table->string('promo_code');
            $table->string('adv_unique1')->nullable();
            $table->string('adv_unique2')->nullable();
            $table->string('adv_unique3')->nullable();
            $table->string('adv_unique4')->nullable();
            $table->string('adv_unique5')->nullable();
            $table->string('order_id')->nullable();
            $table->string('sku_id')->nullable();
            $table->string('product_category')->nullable();
            $table->string('app_version')->nullable();
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
