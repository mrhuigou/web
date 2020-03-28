<?php
namespace fx\models;

use api\models\V1\Affiliate;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $oldpassword;
    public $password;
    public $password_repeat;

    /**
     * @var \common\models\User
     */
    //private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    // public function __construct($token, $config = [])
    // {
    //     if (empty($token) || !is_string($token)) {
    //         throw new InvalidParamException('Password reset token cannot be blank.');
    //     }
    //     $this->_user = User::findByPasswordResetToken($token);
    //     if (!$this->_user) {
    //         throw new InvalidParamException('Wrong password reset token.');
    //     }
    //     parent::__construct($config);
    // }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['oldpassword', 'required'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 20],
            ['password_repeat', 'required'],
            ['password', 'compare'],
            ['oldpassword', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Affiliate::findOne(Yii::$app->user->getId());
            if (!$user || !$user->validatePassword($this->oldpassword)) {
                $this->addError($attribute, '您输入的密码和账户名不匹配，请重新输入.');
                //Yii::$app->getSession()->setFlash('error', '您输入的密码和账户名不匹配，请重新输入。 ');
            }
        }
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword($telephone,$password)
    {
        $user = Affiliate::findOne(Yii::$app->user->getId());
        $salt = substr(md5(uniqid(rand(), true)), 0, 9);
        $user->password = sha1($salt . sha1($salt . sha1($password)));
        $user->salt=$salt;
        //$user->removePasswordResetToken();

        return $user->update();
    }
}
