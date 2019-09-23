<?php
namespace h5\models;

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
class RealNameForm extends Model
{
    public $telephone;
    public $realname;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'required'],
            ['telephone', 'string', 'length' => 11],
            ['telephone','match','pattern'=>'/^1[3456789]{1}\d{9}$/'],
            ['realname', 'required'],
            ['realname', 'string', 'min' => 2],
            ['verifyCode', 'required'],
            ['verifyCode', 'string', 'min' => 4],
            ['verifyCode', 'checkcode'],
        ];
    }
    public function checkcode($attribute, $params){
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
            if(!$model=User::findOne(['telephone'=>$this->telephone])){
                $model = User::findIdentity(['customer_id'=>\Yii::$app->user->getId()]);
            }else{
                if(!$model->photo){
                    $model->photo=Yii::$app->user->identity->photo;
                }
                if(Yii::$app->user->identity->nickname){
                    $model->nickname=Yii::$app->user->identity->nickname;
                }
            }
            $model->firstname=$this->realname;
            $model->telephone = $this->telephone;
            $model->telephone_validate=1;
            $model->status=1;
            $model->approved=1;
            $model->save();
//            $auth=CustomerAuthentication::findOne(['customer_id'=>Yii::$app->user->getId()]);
//            $auth->customer_id=$model->customer_id;
//            $auth->save();
            Yii::$app->user->login($model,60*60*24);
            return true;
        }
        return false;
    }
    public function attributeLabels(){
        return ['telephone'=>'手机号不能为空',
            'realname'=>'真实姓名',
            'verifyCode'=>'手机验证码'
        ];
    }
}
