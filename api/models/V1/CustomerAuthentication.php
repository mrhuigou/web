<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_authentication}}".
 *
 * @property integer $customer_authentication_id
 * @property integer $customer_id
 * @property string $provider
 * @property string $identifier
 * @property string $openid
 * @property string $web_site_url
 * @property string $profile_url
 * @property string $photo_url
 * @property string $display_name
 * @property string $description
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $language
 * @property string $age
 * @property string $birth_day
 * @property string $birth_month
 * @property string $birth_year
 * @property string $email
 * @property string $email_verified
 * @property string $phone
 * @property string $address
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $zip
 * @property string $date_added
 * @property string $date_update
 * @property integer $status
 */
class CustomerAuthentication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_authentication}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provider', 'identifier'], 'required'],
            [['customer_id', 'status'], 'integer'],
            [['date_added','date_update'], 'safe'],
            [['provider'], 'string', 'max' => 100],
            [['identifier', 'openid', 'web_site_url', 'profile_url', 'photo_url', 'display_name', 'description', 'first_name', 'last_name', 'gender', 'language', 'age', 'birth_day', 'birth_month', 'birth_year', 'email', 'email_verified', 'phone', 'address', 'country', 'region', 'city', 'zip'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_authentication_id' => 'Customer Authentication ID',
            'customer_id' => 'Customer ID',
            'provider' => 'Provider',
            'identifier' => 'Identifier',
            'openid' => 'Openid',
            'web_site_url' => 'Web Site Url',
            'profile_url' => 'Profile Url',
            'photo_url' => 'Photo Url',
            'display_name' => 'Display Name',
            'description' => 'Description',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'language' => 'Language',
            'age' => 'Age',
            'birth_day' => 'Birth Day',
            'birth_month' => 'Birth Month',
            'birth_year' => 'Birth Year',
            'email' => 'Email',
            'email_verified' => 'Email Verified',
            'phone' => 'Phone',
            'address' => 'Address',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
            'zip' => 'Zip',
            'date_added' => 'Date Added',
	        'date_update'=>'Date Update',
            'status' => 'Status',
        ];
    }
    public function getUser(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
