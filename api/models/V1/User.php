<?php

namespace api\models\V1;

use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $user_id
 * @property integer $user_group_id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $code
 * @property string $ip
 * @property integer $status
 * @property string $date_added
 * @property string $qq
 * @property string $uname
 * @property string $seckey
 * @property integer $customer_id
 */
class User extends \yii\db\ActiveRecord  implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_group_id', 'username', 'password', 'salt', 'firstname', 'lastname', 'email', 'code', 'ip', 'status'], 'required'],
            [['user_group_id', 'status', 'customer_id'], 'integer'],
            [['date_added'], 'safe'],
            [['username', 'qq'], 'string', 'max' => 20],
            [['password', 'code', 'ip'], 'string', 'max' => 40],
            [['salt'], 'string', 'max' => 9],
            [['firstname', 'lastname'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 96],
            [['uname', 'seckey'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_group_id' => 'User Group ID',
            'username' => 'Username',
            'password' => 'Password',
            'salt' => 'Salt',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'code' => 'Code',
            'ip' => 'Ip',
            'status' => 'Status',
            'date_added' => 'Date Added',
            'qq' => 'Qq',
            'uname' => 'Uname',
            'seckey' => 'Seckey',
            'customer_id' => 'Customer ID',
        ];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ACTIVE]);
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
            return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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
}
