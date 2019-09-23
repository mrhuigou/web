<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_fraud}}".
 *
 * @property integer $order_id
 * @property integer $customer_id
 * @property string $country_match
 * @property string $country_code
 * @property string $high_risk_country
 * @property integer $distance
 * @property string $ip_region
 * @property string $ip_city
 * @property string $ip_latitude
 * @property string $ip_longitude
 * @property string $ip_isp
 * @property string $ip_org
 * @property integer $ip_asnum
 * @property string $ip_user_type
 * @property string $ip_country_confidence
 * @property string $ip_region_confidence
 * @property string $ip_city_confidence
 * @property string $ip_postal_confidence
 * @property string $ip_postal_code
 * @property integer $ip_accuracy_radius
 * @property string $ip_net_speed_cell
 * @property integer $ip_metro_code
 * @property integer $ip_area_code
 * @property string $ip_time_zone
 * @property string $ip_region_name
 * @property string $ip_domain
 * @property string $ip_country_name
 * @property string $ip_continent_code
 * @property string $ip_corporate_proxy
 * @property string $anonymous_proxy
 * @property integer $proxy_score
 * @property string $is_trans_proxy
 * @property string $free_mail
 * @property string $carder_email
 * @property string $high_risk_username
 * @property string $high_risk_password
 * @property string $bin_match
 * @property string $bin_country
 * @property string $bin_name_match
 * @property string $bin_name
 * @property string $bin_phone_match
 * @property string $bin_phone
 * @property string $customer_phone_in_billing_location
 * @property string $ship_forward
 * @property string $city_postal_match
 * @property string $ship_city_postal_match
 * @property string $score
 * @property string $explanation
 * @property string $risk_score
 * @property integer $queries_remaining
 * @property string $maxmind_id
 * @property string $error
 * @property string $date_added
 */
class OrderFraud extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_fraud}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'customer_id', 'country_match', 'country_code', 'high_risk_country', 'distance', 'ip_region', 'ip_city', 'ip_latitude', 'ip_longitude', 'ip_isp', 'ip_org', 'ip_asnum', 'ip_user_type', 'ip_country_confidence', 'ip_region_confidence', 'ip_city_confidence', 'ip_postal_confidence', 'ip_postal_code', 'ip_accuracy_radius', 'ip_net_speed_cell', 'ip_metro_code', 'ip_area_code', 'ip_time_zone', 'ip_region_name', 'ip_domain', 'ip_country_name', 'ip_continent_code', 'ip_corporate_proxy', 'anonymous_proxy', 'proxy_score', 'is_trans_proxy', 'free_mail', 'carder_email', 'high_risk_username', 'high_risk_password', 'bin_match', 'bin_country', 'bin_name_match', 'bin_name', 'bin_phone_match', 'bin_phone', 'customer_phone_in_billing_location', 'ship_forward', 'city_postal_match', 'ship_city_postal_match', 'score', 'explanation', 'risk_score', 'queries_remaining', 'maxmind_id', 'error'], 'required'],
            [['order_id', 'customer_id', 'distance', 'ip_asnum', 'ip_accuracy_radius', 'ip_metro_code', 'ip_area_code', 'proxy_score', 'queries_remaining'], 'integer'],
            [['ip_latitude', 'ip_longitude', 'score', 'risk_score'], 'number'],
            [['explanation', 'error'], 'string'],
            [['date_added'], 'safe'],
            [['country_match', 'high_risk_country', 'ip_country_confidence', 'ip_region_confidence', 'ip_city_confidence', 'ip_postal_confidence', 'ip_corporate_proxy', 'anonymous_proxy', 'is_trans_proxy', 'free_mail', 'carder_email', 'high_risk_username', 'high_risk_password', 'bin_name_match', 'bin_phone_match', 'ship_forward', 'city_postal_match', 'ship_city_postal_match'], 'string', 'max' => 3],
            [['country_code', 'ip_continent_code', 'bin_country'], 'string', 'max' => 2],
            [['ip_region', 'ip_city', 'ip_isp', 'ip_org', 'ip_user_type', 'ip_net_speed_cell', 'ip_time_zone', 'ip_region_name', 'ip_domain', 'ip_country_name', 'bin_name'], 'string', 'max' => 255],
            [['ip_postal_code', 'bin_match'], 'string', 'max' => 10],
            [['bin_phone'], 'string', 'max' => 32],
            [['customer_phone_in_billing_location', 'maxmind_id'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'customer_id' => 'Customer ID',
            'country_match' => 'Country Match',
            'country_code' => 'Country Code',
            'high_risk_country' => 'High Risk Country',
            'distance' => 'Distance',
            'ip_region' => 'Ip Region',
            'ip_city' => 'Ip City',
            'ip_latitude' => 'Ip Latitude',
            'ip_longitude' => 'Ip Longitude',
            'ip_isp' => 'Ip Isp',
            'ip_org' => 'Ip Org',
            'ip_asnum' => 'Ip Asnum',
            'ip_user_type' => 'Ip User Type',
            'ip_country_confidence' => 'Ip Country Confidence',
            'ip_region_confidence' => 'Ip Region Confidence',
            'ip_city_confidence' => 'Ip City Confidence',
            'ip_postal_confidence' => 'Ip Postal Confidence',
            'ip_postal_code' => 'Ip Postal Code',
            'ip_accuracy_radius' => 'Ip Accuracy Radius',
            'ip_net_speed_cell' => 'Ip Net Speed Cell',
            'ip_metro_code' => 'Ip Metro Code',
            'ip_area_code' => 'Ip Area Code',
            'ip_time_zone' => 'Ip Time Zone',
            'ip_region_name' => 'Ip Region Name',
            'ip_domain' => 'Ip Domain',
            'ip_country_name' => 'Ip Country Name',
            'ip_continent_code' => 'Ip Continent Code',
            'ip_corporate_proxy' => 'Ip Corporate Proxy',
            'anonymous_proxy' => 'Anonymous Proxy',
            'proxy_score' => 'Proxy Score',
            'is_trans_proxy' => 'Is Trans Proxy',
            'free_mail' => 'Free Mail',
            'carder_email' => 'Carder Email',
            'high_risk_username' => 'High Risk Username',
            'high_risk_password' => 'High Risk Password',
            'bin_match' => 'Bin Match',
            'bin_country' => 'Bin Country',
            'bin_name_match' => 'Bin Name Match',
            'bin_name' => 'Bin Name',
            'bin_phone_match' => 'Bin Phone Match',
            'bin_phone' => 'Bin Phone',
            'customer_phone_in_billing_location' => 'Customer Phone In Billing Location',
            'ship_forward' => 'Ship Forward',
            'city_postal_match' => 'City Postal Match',
            'ship_city_postal_match' => 'Ship City Postal Match',
            'score' => 'Score',
            'explanation' => 'Explanation',
            'risk_score' => 'Risk Score',
            'queries_remaining' => 'Queries Remaining',
            'maxmind_id' => 'Maxmind ID',
            'error' => 'Error',
            'date_added' => 'Date Added',
        ];
    }
}
