<?php
namespace api\models\form;
use api\models\V1\VerifyCode;
use common\models\User;
use yii\base\Model;
use Yii;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $telephone;
    public $password;
    public $code;
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
            ['telephone', 'filter', 'filter' => 'trim'],
            [['telephone','password','code'], 'required'],
            ['telephone', 'string', 'length' => 11],
            ['telephone', 'unique', 'targetClass' => '\common\models\User', 'message' => '此手机号已经注册过了！'],
            ['password', 'string', 'min' => 6],
            ['code', 'checkcode'],
        ];
    }
    public function checkcode($attribute){
        if($model=VerifyCode::findOne(['phone'=>$this->telephone,'code'=>$this->code,'status'=>0])){
            $model->status=1;
            $model->update();
        }else{
            $this->addError($attribute,'验证码不正确！');
        }
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $user = new User();
            $user->nickname="JR".time();
            $user->telephone = $this->telephone;
            $user->telephone_validate=1;
            $user->status=1;
            $user->approved=1;
            $user->customer_group_id=1;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->date_added=date('Y-m-d H:i:s',time());
            $user->save();
            return $user;
        }
        return null;
    }
    public function attributeLabels(){
        return ['telephone'=>'手机号码',
            'password'=>'密码',
            'code'=>'验证码',
        ];
    }
}
