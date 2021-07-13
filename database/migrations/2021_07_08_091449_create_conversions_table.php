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

            $table->id();
            $table->string('Stat_tune_event_id', 50)->unique()->comment('UNIQUE ID');
            $table->string('Advertiser_ref_id', 50)->nullable();
            $table->string('AdvertiserManager_full_name', 100)->nullable();
            $table->string('Affiliate_company', 100)->nullable();
            $table->string('Advertiser_company', 100)->nullable();
            $table->string('Affiliate_ref_id', 100)->nullable();
            $table->string('AffiliateManager_full_name', 100)->nullable();
            $table->string('Browser_display_name', 100)->nullable();
            $table->string('ConversionMeta_note', 100)->nullable();
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
            $table->string('ConversionsMobile_android_id', 100)->nullable();
            $table->string('ConversionsMobile_android_id_md5', 40)->nullable();
            $table->string('ConversionsMobile_android_id_sha1', 40)->nullable();
            $table->string('ConversionsMobile_device_brand', 50)->nullable();
            $table->string('ConversionsMobile_device_id', 100)->nullable();
            $table->string('ConversionsMobile_device_id_md5', 40)->nullable();
            $table->string('ConversionsMobile_device_id_sha1', 40)->nullable();
            $table->string('ConversionsMobile_device_model', 50)->nullable();
            $table->string('ConversionsMobile_device_os', 50)->nullable();
            $table->string('ConversionsMobile_device_os_version', 50)->nullable();
            $table->string('ConversionsMobile_fraud_reason_data', 50)->nullable();
            $table->string('ConversionsMobile_google_aid', 100)->nullable();
            $table->string('ConversionsMobile_google_aid_md5', 40)->nullable();
            $table->string('ConversionsMobile_google_aid_sha1', 40)->nullable();
            $table->string('ConversionsMobile_ios_ifa', 100)->nullable();
            $table->string('ConversionsMobile_ios_ifa_md5', 40)->nullable();
            $table->string('ConversionsMobile_ios_ifa_sha1', 40)->nullable();
            $table->string('ConversionsMobile_ios_ifv', 100)->nullable();
            $table->string('ConversionsMobile_mac_address', 50)->nullable();
            $table->string('ConversionsMobile_mac_address_md5', 40)->nullable();
            $table->string('ConversionsMobile_mac_address_sha1', 40)->nullable();
            $table->string('ConversionsMobile_mobile_carrier', 100)->nullable();
            $table->string('ConversionsMobile_odin', 100)->nullable();
            $table->string('ConversionsMobile_unknown_id', 100)->nullable();
            $table->string('ConversionsMobile_user_id', 100)->nullable();
            $table->string('ConversionsMobile_windows_aid', 100)->nullable();
            $table->string('ConversionsMobile_windows_aid_md5', 40)->nullable();
            $table->string('ConversionsMobile_windows_aid_sha1', 40)->nullable();
            $table->string('Country_name', 100)->nullable();
            $table->string('Customer_provided_id', 100)->nullable();
            $table->string('Goal_name', 100)->nullable();
            $table->string('Offer_name', 100)->nullable();
            $table->string('Offer_ref_id', 100)->nullable();
            $table->string('OfferUrl_name', 100)->nullable();
            $table->string('OfferUrl_preview_url', 100)->nullable();
            $table->string('PayoutGroup_id', 100)->nullable();
            $table->string('PayoutGroup_name', 100)->nullable();
            $table->string('Region_name', 100)->nullable();
            $table->string('RevenueGroup_id', 100)->nullable();
            $table->string('RevenueGroup_name', 100)->nullable();
            $table->string('Stat_ad_id', 100)->nullable();
            $table->string('Stat_affiliate_manager_id', 100)->nullable();
            $table->string('Stat_advertiser_id', 100)->nullable();
            $table->string('Stat_advertiser_info', 100)->nullable();
            $table->string('Stat_advertiser_manager_id', 100)->nullable();
            $table->string('Stat_affiliate_id', 100)->nullable();
            $table->string('Stat_affiliate_info1', 100)->nullable();
            $table->string('Stat_affiliate_info2', 100)->nullable();
            $table->string('Stat_affiliate_info3', 100)->nullable();
            $table->string('Stat_affiliate_info4', 100)->nullable();
            $table->string('Stat_affiliate_info5', 100)->nullable();
            $table->string('Stat_browser_id', 100)->nullable();
            $table->string('Stat_city_name', 100)->nullable();
            $table->string('Stat_country_code', 10)->nullable();
            $table->string('Stat_currency', 10)->nullable();
            $table->tinyInteger('Stat_customer_id')->nullable();
            $table->tinyInteger('Stat_count')->nullable();
            $table->tinyInteger('Stat_count_adjustment')->nullable();
            $table->tinyInteger('Stat_count_approved')->nullable();
            $table->tinyInteger('Stat_count_pending')->nullable();
            $table->tinyInteger('Stat_count_rejected')->nullable();
            $table->tinyInteger('Stat_creative_url_id')->nullable();
            $table->tinyInteger('Stat_goal_id')->nullable();
            $table->tinyInteger('Stat_hour')->nullable();
            $table->tinyInteger('Stat_is_adjustment')->nullable();
            $table->decimal('Stat_approved_payout', 15, 5)->nullable();
            $table->decimal('Stat_approved_rate', 15, 5)->nullable();
            $table->decimal('Stat_net_payout', 15, 5)->nullable();
            $table->decimal('Stat_net_revenue', 15, 5)->nullable();
            $table->decimal('Stat_net_sale_amount', 15, 5)->nullable();
            $table->decimal('Stat_payout', 15, 5)->nullable();
            $table->decimal('Stat_pending_payout', 15, 5)->nullable();
            $table->decimal('Stat_pending_revenue', 15, 5)->nullable();
            $table->decimal('Stat_pending_sale_amount', 15, 5)->nullable();
            $table->decimal('Stat_rejected_rate', 15, 5)->nullable();
            $table->decimal('Stat_revenue', 15, 5)->nullable();
            $table->decimal('Stat_sale_amount', 15, 5)->nullable();
            $table->decimal('Stat_payout@USD', 15, 5)->nullable();
            $table->decimal('Stat_revenue@USD', 15, 5)->nullable();
            $table->decimal('Stat_sale_amount@USD"', 15, 5)->nullable();
            $table->decimal('Stat_net_payout@USD"', 15, 5)->nullable();
            $table->decimal('Stat_net_revenue@USD"', 15, 5)->nullable();
            $table->decimal('Stat_net_sale_amount@USD"', 15, 5)->nullable();
            $table->decimal('Stat_pending_payout@USD"', 15, 5)->nullable();
            $table->decimal('Stat_approved_payout@USD"', 15, 5)->nullable();
            $table->decimal('Stat_pending_revenue@USD"', 15, 5)->nullable();
            $table->decimal('Stat_pending_sale_amount@USD"', 15, 5)->nullable();
            $table->date('Stat_date')->nullable();
            $table->date('Stat_session_date')->nullable();
            $table->dateTime('Stat_datetime')->nullable();
            $table->dateTime('Stat_session_datetime')->nullable();
            $table->string('Stat_datetime_diff', 20)->nullable();
            $table->string('Stat_id', 50)->nullable();
            $table->string('Stat_ip', 50)->nullable();
            $table->string('Stat_month', 10)->nullable();
            $table->string('Stat_offer_id', 50)->nullable();
            $table->string('Stat_offer_url_id', 50)->nullable();
            $table->string('Stat_payout_type', 50)->nullable();
            $table->string('Stat_pixel_refer', 100)->nullable();
            $table->string('Stat_refer', 100)->nullable();
            $table->string('Stat_region_code', 10)->nullable();
            $table->string('Stat_revenue_type', 10)->nullable();
            $table->string('Stat_session_ip', 50)->nullable();
            $table->string('Stat_source', 50)->nullable();
            $table->string('Stat_status', 50)->nullable();
            $table->string('Stat_status_code', 10)->nullable();
            $table->string('Stat_user_agent', 255)->nullable();
            $table->string('Stat_week', 20)->nullable();
            $table->string('Stat_year', 10)->nullable();
            $table->timestamps();

            /*  foreach(\App\Models\Conversion::FIELDS as $field)
              {
                  if($field == 'Stat.tune_event_id') continue;
                  if($field == 'Offer.name') {
                      $table->string($field, 255)->nullable();
                      continue;
                  }
                  if( strpos( $field, "payout")!==false
                     ||  strpos( $field, "revenue")!==false
                      ||  strpos( $field, "amount")!==false
                   )
                  {
                      $table->decimal($field, 15, 5)->default(0.00000);
                      continue;
                  }
                  if(strpos( $field, "payout")!==false) {
                      $table->decimal($field, 15, 5)->default(0.00000);
                      continue;
                  }
                  $table->string('Advertiser.company', 50)->nullable();

              }

          });*/
            /*  Schema::create('conversions', function (Blueprint $table) {
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
              });*/
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
