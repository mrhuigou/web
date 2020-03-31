<?php

namespace api\models\V1;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%affiliate}}".
 *
 * @property integer $affiliate_id
 * @property string $username
 * @property string $email
 * @property string $telephone
 * @property string $password
 * @property string $salt
 * @property string $code
 * @property string $commission
 * @property string $ip
 * @property string $parent_id
 * @property integer $status
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $date_added
 */
class Affiliate extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate}}';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'affiliate_id' => 'ID',
            'username' => '姓名',
            'email' => 'Email',
            'telephone' => '电话',
            'password' => '密码',
            'salt' => 'Salt',
            'code' => '身份识别编码',
            'commission' => '佣金',
            'ip' => 'Ip',
            'status' => '状态',
            'date_added' => '创建时间',
            'mode' => '模式',
            'address' => '详细地址',
        ];
    }

      /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['affiliate_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {        

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
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {  
       $password_hash=sha1($this->salt . sha1($this->salt . sha1($password)));
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
        $this->password = sha1($salt . sha1($salt . sha1($password)));
        $this->salt=$salt;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
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

    public function getCustomer(){
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }


}
