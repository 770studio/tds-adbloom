<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    const UPDATE_STARTING_FROM_LAST_X_MONTHS = 0.01;
    const FIELDS = ['Advertiser.company','Advertiser.ref_id','AdvertiserManager.full_name','Affiliate.company','Affiliate.ref_id','AffiliateManager.full_name','Browser.display_name','ConversionMeta.note','ConversionsMobile.adv_sub2','ConversionsMobile.adv_sub3','ConversionsMobile.adv_sub4','ConversionsMobile.adv_sub5','ConversionsMobile.affiliate_click_id','ConversionsMobile.affiliate_unique1','ConversionsMobile.affiliate_unique2','ConversionsMobile.affiliate_unique3','ConversionsMobile.affiliate_unique4','ConversionsMobile.affiliate_unique5','ConversionsMobile.android_id','ConversionsMobile.android_id_md5','ConversionsMobile.android_id_sha1','ConversionsMobile.device_brand','ConversionsMobile.device_id','ConversionsMobile.device_id_md5','ConversionsMobile.device_id_sha1','ConversionsMobile.device_model','ConversionsMobile.device_os','ConversionsMobile.device_os_version','ConversionsMobile.fraud_reason_data','ConversionsMobile.google_aid','ConversionsMobile.google_aid_md5','ConversionsMobile.google_aid_sha1','ConversionsMobile.ios_ifa','ConversionsMobile.ios_ifa_md5','ConversionsMobile.ios_ifa_sha1','ConversionsMobile.ios_ifv','ConversionsMobile.mac_address','ConversionsMobile.mac_address_md5','ConversionsMobile.mac_address_sha1','ConversionsMobile.mobile_carrier','ConversionsMobile.odin','ConversionsMobile.unknown_id','ConversionsMobile.user_id','ConversionsMobile.windows_aid','ConversionsMobile.windows_aid_md5','ConversionsMobile.windows_aid_sha1','Country.name','Customer.provided_id','Goal.name','Offer.name','Offer.ref_id','OfferUrl.name','OfferUrl.preview_url','PayoutGroup.id','PayoutGroup.name','Region.name','RevenueGroup.id','RevenueGroup.name','Stat.ad_id','Stat.advertiser_id','Stat.advertiser_info','Stat.advertiser_manager_id','Stat.affiliate_id','Stat.affiliate_info1','Stat.affiliate_info2','Stat.affiliate_info3','Stat.affiliate_info4','Stat.affiliate_info5','Stat.affiliate_manager_id','Stat.approved_payout','Stat.approved_rate','Stat.browser_id','Stat.city_name','Stat.count','Stat.count_adjustment','Stat.count_approved','Stat.count_pending','Stat.count_rejected','Stat.country_code','Stat.creative_url_id','Stat.currency','Stat.customer_id','Stat.date','Stat.datetime','Stat.datetime_diff','Stat.goal_id','Stat.hour','Stat.id','Stat.ip','Stat.is_adjustment','Stat.month','Stat.net_payout','Stat.net_revenue','Stat.net_sale_amount','Stat.offer_id','Stat.offer_url_id','Stat.payout','Stat.payout_type','Stat.pending_payout','Stat.pending_revenue','Stat.pending_sale_amount','Stat.pixel_refer','Stat.refer','Stat.region_code','Stat.rejected_rate','Stat.revenue','Stat.revenue_type','Stat.sale_amount','Stat.session_date','Stat.session_datetime','Stat.session_ip','Stat.source','Stat.status','Stat.status_code','Stat.tune_event_id','Stat.user_agent','Stat.week','Stat.year'];
    const ID_FIELD = 'Stat.tune_event_id';
    protected $guarded = [];
    protected $primaryKey = self::ID_FIELD;

}
