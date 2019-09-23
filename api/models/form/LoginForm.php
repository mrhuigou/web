<?php
namespace api\models\form;
use common\models\User;
use yii\base\Model;
use Yii;
/**
 * LoginForm
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    private $_user = false;
    public function __construct($config = [])
    {
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['username','password'], 'required'],
            ['password', 'validatePassword']
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '您输入的密码和账户名不匹配，请重新输入.');
            }
        }
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
    public function login()
    {
        if ($this->validate()) {
            return $this->_user;
        } else {
            return false;
        }
    }
    public function attributeLabels(){
        return ['username'=>'手机号码/邮箱',
            'password'=>'密码',
        ];
    }
}
