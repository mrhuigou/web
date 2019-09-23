<?php
namespace common\models;

use api\models\V1\Customer;
use api\models\V1\VerifyCode;
use yii\base\Model;
use Yii;

/**
 * RealNameForm
 */
class SecuritySetEmailForm extends Model
{
    public $email;
    public $verifyCode;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'checkemail'],
            ['verifyCode', 'required'],
            ['verifyCode', 'string', 'length' => 6],
            ['verifyCode', 'checkcode'],

        ];
    }

    public function checkemail($attribute){
        $customer = Customer::find()->where(['and','customer_id <>'.Yii::$app->user->getId(),'email="'.$this->email.'"'])->one();
        if(!empty($customer)){
            $this->addError($attribute,'邮箱已经存在！');
        }

    }
    public function checkcode($attribute, $params){
        if($model=VerifyCode::findOne(['phone'=>$this->email,'code'=>$this->verifyCode,'status'=>0])){
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
    public function bindName()
    {
        if ($this->validate()) {
	        $model = User::findIdentity(Yii::$app->user->getId());
            $model->email = $this->email;
            $model->email_validate=1;
            $model->status=1;
            $model->approved=1;
            $model->save();
            return true;
        }
        return false;
    }
    public function attributeLabels(){
        return ['email'=>'邮箱',
            'verifyCode'=>'验证码'
        ];
    }
}
