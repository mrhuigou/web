<?php
namespace common\models;

use api\models\V1\CustomerAffiliate;
use api\models\V1\CustomerAuthentication;
use api\models\V1\CustomerCommission;
use api\models\V1\CustomerTransaction;
use api\models\V1\Message;
use api\models\V1\News;
use api\models\V1\NewsLog;
use api\modules\oauth2\models\OauthAccessTokens;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $user_agent
 * @property string $can_use_cod
 */
class User extends ActiveRecord implements IdentityInterface,\OAuth2\Storage\UserCredentialsInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    //const ROLE_USER = 10;
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
            [['store_id','can_use_cod', 'email_validate', 'telephone_validate', 'zone_id', 'city_id', 'district_id', 'idcard_validate', 'newsletter', 'address_id', 'customer_group_id', 'status', 'approved', 'forget_link_validity', 'total_follow', 'total_follower', 'total_exp', 'total_comment', 'total_album', 'total_favorite_shares', 'total_favorite_albums', 'is_star', 'usergroup_id', 'credits', 'ext_credits_1', 'ext_credits_2', 'ext_credits_3', 'points', 'customer_level_id', 'custom', 'id','customer_id', 'authen_business'], 'integer'],
            [['birthday', 'date_added'], 'safe'],
            [['password', 'salt'], 'required'],
            [['cart', 'wishlist', 'favourite_stores','user_agent'], 'string'],
            [['longitude', 'latitude'], 'number'],
            [['firstname', 'lastname', 'telephone', 'fax'], 'string', 'max' => 32],
            [['nickname', 'onmobile', 'email'], 'string', 'max' => 100],
            [['gender', 'education', 'occupation', 'idcard', 'token', 'code', 'signature', 'source_from', 'company_name', 'company_no', 'legel_name'], 'string', 'max' => 255],
            [['password', 'ip', 'paymentpwd'], 'string', 'max' => 40],
            [['salt', 'psalt'], 'string', 'max' => 9],
            [['photo'], 'string', 'max' => 255],
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
            'password_reset_token' => $token,
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
        return $this->auth_key;
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
    // public function validatePassword($password)
    // {
    //     return Yii::$app->security->validatePassword($password, $this->password_hash);
    // }

    public function validatePassword($password)
    {  
       $password_hash=sha1($this->salt . sha1($this->salt . sha1($password)));
       if($this->password==$password_hash){
           return true;
       }else{
           return  $this->password==md5($password);
       }
    }

    public function validatePayPassword($password)
    {  
       $password_hash=sha1($this->psalt . sha1($this->psalt . sha1($password)));
       if($this->paymentpwd==$password_hash){
           return true;
       }else{
           return  $this->paymentpwd==md5($password);
       }
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    // public function setPassword($password)
    // {
    //     $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    // }

    public function setPassword($password)
    {
        $salt = substr(md5(uniqid(rand(), true)), 0, 9);
        $this->password = sha1($salt . sha1($salt . sha1($password)));
        $this->salt=$salt;
    }

    public function setPayPassword($password)
    {
        $salt = substr(md5(uniqid(rand(), true)), 0, 9);
        $this->paymentpwd = sha1($salt . sha1($salt . sha1($password)));
        $this->psalt=$salt;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /* OAuth2\Storage\UserCredentialsInterface */
    public function checkUserCredentials($username, $password)
    {
        if ($user = $this->findByUsername($username)) {
            return $user->validatePassword($password);
        }

        return false;
    }
    public function getSubcription(){
        if($model=CustomerAuthentication::findOne(['customer_id'=>$this->customer_id,'status'=>1,'provider'=>'WeiXin'])){
            return true;
        }else{
            return false;
        }
    }
    public function getWxOpenId(){
        if($model=CustomerAuthentication::findOne(['customer_id'=>$this->customer_id,'provider'=>'WeiXin','status'=>1])){
            return $model->openid;
        }else{
            return false;
        }
    }
    public function getUserDetails($username)
    {
        return array(
            'user_id' => $username,
            'username'=>$username,
        );
    }
    public function getBalance(){
        $model=CustomerTransaction::find()->where(['customer_id'=>$this->customer_id]);
        if($model){
            return max(0,$model->sum('amount'));
        }else{
            return 0;
        }
    }

    public function getUsername(){
        $customer_id = $this->getId();
        $customer = static::findOne($customer_id);
        if($customer->nickname){
            return $customer->nickname;
        }elseif($customer->telephone){
            return $customer->telephone;
        }elseif($customer->email){
            return $customer->email;
        }
    }
	public function  getMessageCount(){
		$count = 0;
		if(!Yii::$app->user->isGuest){
			$list=[];
			if($list_model=NewsLog::find()->where(['customer_id'=>$this->customer_id])->all()){
				foreach ($list_model as $value){
					$list[]=$value->new_id;
				}
			}
			$count=News::find()->where(['not in','news_id',$list])->andWhere(['channel'=>1,'status'=>1])->count();
		}
		return $count;
	}
	public function getAffiliate(){
		return $this->hasOne(CustomerAffiliate::className(),['customer_id'=>'customer_id']);
	}
	public function getCommission(){
		$model=CustomerCommission::find()->where(['customer_id'=>$this->customer_id]);
		if($model){
			return max(0,$model->sum('amount'));
		}else{
			return 0;
		}
	}
}
