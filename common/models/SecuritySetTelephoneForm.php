<?php
namespace common\models;

use api\models\V1\Customer;
use api\models\V1\CustomerAuthentication;
use api\models\V1\VerifyCode;
use yii\base\Model;
use Yii;

/**
 * RealNameForm
 */
class SecuritySetTelephoneForm extends Model
{
    public $telephone;
    public $verifyCode;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['telephone', 'filter', 'filter' => 'trim'],
            [['telephone','verifyCode'], 'required'],
            ['telephone', 'string', 'length' => 11],
	        ['verifyCode', 'string', 'min' => 4],
            ['telephone','match','pattern'=>'/^1[3456789]{1}\d{9}$/'],
	        ['telephone', 'checktelephone'],
            ['verifyCode', 'checkcode'],
        ];
    }
    public function checktelephone($attribute){
        $customer = Customer::find()->where(['and','customer_id <>'.Yii::$app->user->getId(),'telephone='.$this->telephone])->one();
        if(!empty($customer)){
            $this->addError($attribute,'手机号码已经存在！');
        }
    }
    public function checkcode($attribute){
        if($model=VerifyCode::findOne(['phone'=>$this->telephone,'code'=>$this->verifyCode,'status'=>0])){
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
	        $model = User::findIdentity(\Yii::$app->user->getId());
	        $model->telephone = $this->telephone;
	        $model->telephone_validate=1;
	        $model->status=1;
	        $model->approved=1;
	        $model->save();
	        return $model;
        }
        return false;
    }
    public function attributeLabels(){
        return ['telephone'=>'手机号',
            'verifyCode'=>'手机验证码'
        ];
    }
}
