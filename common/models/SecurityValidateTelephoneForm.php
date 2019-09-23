<?php
namespace common\models;

use api\models\V1\Customer;
use api\models\V1\CustomerAuthentication;
use api\models\V1\VerifyCode;
use common\models\User;
use yii\base\Model;
use Yii;
use yii\helpers\Json;

/**
 * RealNameForm
 */
class SecurityValidateTelephoneForm extends Model
{
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['verifyCode', 'required'],
            ['verifyCode', 'string', 'min' => 4],
            ['verifyCode', 'checkcode'],
        ];
    }
    public function checkcode($attribute){
        if($model=VerifyCode::findOne(['phone'=> Yii::$app->user->getIdentity()->telephone,'code'=>$this->verifyCode,'status'=>0])){
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
            if($model=User::findIdentity(Yii::$app->user->getId())){
                $model->telephone_validate=1;
                $model->save();
                return true;
            }else{
                return false;
            }
        }
        return false;
    }
    public function attributeLabels(){
        return [
            'verifyCode'=>'手机验证码'
        ];
    }
}
