<?php
namespace frontend\models;


use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class MerchantForm extends Model
{
    public $name;
    public $merchant;
    public $telephone;
    public $type;//供货类型
    public $email;
    public $othercontacts;
    public $content; //合作意向
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'min' => 2],
            ['merchant', 'required'],
            ['merchant', 'string', 'min' => 2],
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'required'],
            ['telephone', 'string', 'length' => 11],
            ['telephone','match','pattern'=>'/^1[34578]{1}\d{9}$/'],
            ['type', 'required'],
            ['email', 'required',],
            ['othercontacts', 'required'],
            ['content', 'required'],
            ['content', 'string','min'=>6],
            ['verifyCode','string','length'=>4],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function submitreturn()
    {
        if ($this->validate()) {
           //do save
        }
        return null;
    }
    public function attributeLabels(){
        return ['telephone'=>'手机号码',
            'content'=>'合作意向',
            'name' => '真实姓名',
            'email' => '联系邮箱',
            'merchant' => '商家名称'

        ];
    }
}
