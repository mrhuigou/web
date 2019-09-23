<?php

namespace api\models\V1;

use api\modules\oauth2\models\OauthAccessTokens;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "{{%customer}}".
 *
 * @property integer $customer_id
 * @property integer $store_id
 * @property string $firstname
 * @property string $lastname
 * @property string $nickname
 * @property string $onmobile
 * @property string $email
 * @property integer $email_validate
 * @property string $telephone
 * @property integer $telephone_validate
 * @property string $gender
 * @property string $birthday
 * @property integer $zone_id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $education
 * @property string $occupation
 * @property string $idcard
 * @property integer $idcard_validate
 * @property string $fax
 * @property string $password
 * @property string $salt
 * @property string $cart
 * @property string $wishlist
 * @property integer $newsletter
 * @property integer $address_id
 * @property integer $customer_group_id
 * @property string $ip
 * @property integer $status
 * @property integer $approved
 * @property string $token
 * @property string $date_added
 * @property string $code
 * @property double $longitude
 * @property double $latitude
 * @property integer $forget_link_validity
 * @property string $paymentpwd
 * @property string $psalt
 * @property string $favourite_stores
 * @property string $total_follow
 * @property string $total_follower
 * @property string $total_exp
 * @property string $total_comment
 * @property string $total_album
 * @property integer $total_favorite_shares
 * @property integer $total_favorite_albums
 * @property integer $is_star
 * @property integer $usergroup_id
 * @property integer $credits
 * @property integer $ext_credits_1
 * @property integer $ext_credits_2
 * @property integer $ext_credits_3
 * @property integer $points
 * @property integer $customer_level_id
 * @property string $photo
 * @property string $signature
 * @property string $timeline_bg
 * @property integer $custom
 * @property string $source_from
 * @property integer $id
 * @property integer $authen_business
 * @property string $company_name
 * @property string $company_no
 * @property string $legel_name
 */
class Customer extends ActiveRecord implements IdentityInterface,\OAuth2\Storage\UserCredentialsInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'can_use_cod','email_validate', 'telephone_validate', 'zone_id', 'city_id', 'district_id', 'idcard_validate', 'newsletter', 'address_id', 'customer_group_id', 'status', 'approved', 'forget_link_validity', 'total_follow', 'total_follower', 'total_exp', 'total_comment', 'total_album', 'total_favorite_shares', 'total_favorite_albums', 'is_star', 'usergroup_id', 'credits', 'ext_credits_1', 'ext_credits_2', 'ext_credits_3', 'points', 'customer_level_id', 'custom', 'id', 'authen_business'], 'integer'],
            [['birthday', 'date_added'], 'safe'],
            [['password', 'salt'], 'required'],
            [['cart', 'wishlist', 'favourite_stores'], 'string'],
            [['longitude', 'latitude'], 'number'],
            [['firstname', 'lastname', 'telephone', 'fax'], 'string', 'max' => 32],
            [['nickname', 'onmobile', 'email'], 'string', 'max' => 100],
            [['gender', 'education', 'occupation', 'idcard', 'token', 'code', 'source_from', 'company_name', 'company_no', 'legel_name'], 'string', 'max' => 255],
            ['signature', 'string', 'max' => 50],
            [['password', 'ip', 'paymentpwd'], 'string', 'max' => 40],
            [['salt', 'psalt'], 'string', 'max' => 9],
            [['timeline_bg'], 'string', 'max' => 64]
        ];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['customer_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $oauth_user=OauthAccessTokens::findOne(['access_token' => $token]);
        if($oauth_user){
            if($oauth_user->user_id){
                return static::findOne(['customer_id' => $oauth_user->user_id]);
            }else{
                return ;
            }
        }else{
            return;
        }
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if(strpos($username,'@')){
            return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
        }else{
            return static::findOne(['telephone' => $username, 'status' => self::STATUS_ACTIVE]);
        }

    }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'forget_link_validity' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {    $password_hash=sha1($this->salt . sha1($this->salt . sha1($password)));
       if($this->password==$password_hash){
           return true;
       }else{
           return  $this->password==md5($password);
       }
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $salt = substr(md5(uniqid(rand(), true)), 0, 9);
        $password = sha1($salt . sha1($salt . sha1($password)));
        $this->salt=$salt;
        $this->password = $password;
        return $password;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->token = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->forget_link_validity = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->forget_link_validity = null;
    }


    /* OAuth2\Storage\UserCredentialsInterface */
    public function checkUserCredentials($username, $password)
    {
        if ($user = $this->findByUsername($username)) {
             return $user->validatePassword($password);
        }

        return false;
    }

    public function getUserDetails($username)
    {
        $user = $this->findByUsername($username);
        return array(
            'user_id' => $user->customer_id,
            'scope'=>'',
        );
    }
    //通过银联实名认证的
    public function getCustomerUmsauth(){
        return $this->hasOne(CustomerUmsauth::className(), ['customer_id' => 'customer_id'])->andWhere(['status'=>1]);
    }
    //判断是否有
    public function getCustomerSecurityQuestion(){
        return $this->hasMany(CustomerSecurityquestion::className(), ['customer_id' => 'customer_id']);
    }
    public function getClubActivity(){
        return $this->hasMany(ClubActivity::className(),['customer_id'=>'customer_id']);
    }
    public function getClubActivityUserQty(){
        $qty=0;
        if($this->clubActivity){
            foreach($this->clubActivity as $activity){
                $qty+=count($activity->user);
            }
        }
        return $qty;
    }

    public function getWxOpenId(){
        if($model=CustomerAuthentication::findOne(['customer_id'=>$this->customer_id,'provider'=>'WeiXin'])){
            return $model->openid;
        }else{
            return false;
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => '用户 ID',
            'store_id' => '店铺 ID',
            'firstname' => '姓名',
            'lastname' => 'Lastname',
            'nickname' => '昵称',
            'onmobile' => 'Onmobile',
            'email' => '邮箱',
            'email_validate' => '邮箱验证',
            'telephone' => '电话',
            'telephone_validate' => '电话验证',
            'gender' => '性别',
            'birthday' => '生日',
            'zone_id' => 'Zone ID',
            'city_id' => 'City ID',
            'district_id' => '区 ID',
            'education' => 'Education',
            'occupation' => 'Occupation',
            'idcard' => '身份证',
            'idcard_validate' => '身份认证',
            'fax' => 'Fax',
            'password' => 'Password',
            'salt' => 'Salt',
            'cart' => 'Cart',
            'wishlist' => 'Wishlist',
            'newsletter' => 'Newsletter',
            'address_id' => '默认地址 ID',
            'customer_group_id' => 'Customer Group ID',
            'ip' => 'Ip',
            'status' => 'Status',
            'approved' => 'Approved',
            'token' => 'Token',
            'date_added' => 'Date Added',
            'code' => 'Code',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'forget_link_validity' => 'Forget Link Validity',
            'paymentpwd' => 'Paymentpwd',
            'psalt' => 'Psalt',
            'favourite_stores' => 'Favourite Stores',
            'total_follow' => 'Total Follow',
            'total_follower' => 'Total Follower',
            'total_exp' => 'Total Exp',
            'total_comment' => 'Total Comment',
            'total_album' => 'Total Album',
            'total_favorite_shares' => 'Total Favorite Shares',
            'total_favorite_albums' => 'Total Favorite Albums',
            'is_star' => 'Is Star',
            'usergroup_id' => 'Usergroup ID',
            'credits' => 'Credits',
            'ext_credits_1' => 'Ext Credits 1',
            'ext_credits_2' => 'Ext Credits 2',
            'ext_credits_3' => 'Ext Credits 3',
            'points' => 'Points',
            'customer_level_id' => 'Customer Level ID',
            'photo' => '头像',
            'signature' => '个性签名',
            'timeline_bg' => 'Timeline Bg',
            'custom' => 'Custom',
            'source_from' => 'Source From',
            'id' => 'ID',
            'authen_business' => 'Authen Business',
            'company_name' => 'Company Name',
            'company_no' => 'Company No',
            'legel_name' => 'Legel Name',
        ];
    }
} 