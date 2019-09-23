<?php
namespace affiliate\models;

use api\models\V1\Affiliate;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use yii\helpers\Json;

/**
 * Password reset form
 */
class AffiliateForm extends Model
{
    public $username;
    public $code;
    public $email;
    public $telephone;
    public $password;
    public $status=1;
    public $commission;
    public $_user;
     public function __construct($token=0, $config = [])
     {
         if($token){
             if($model=Affiliate::findOne(['affiliate_id'=>$token])){
                 $this->_user=$model;
                 $this->code=$model->code;
                 $this->username=$model->username;
                 $this->email=$model->email;
                 $this->telephone=$model->telephone;
                 $this->commission=$model->commission;
                 $this->status=$model->status;
             }else{
                 throw new InvalidParamException('参数错误');
             }
         }
         parent::__construct($config);
     }
    public function scenarios()
    {
        return [
            'create' => ['username','code', 'telephone', 'email','password','commission'],
            'update' => ['username', 'password','commission'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','telephone','email','code','password'], 'required','on'=>['create']],
            ['password', 'string', 'min' => 6, 'max' => 20],
            ['username', 'string'],
            ['email', 'email'],
            [['commission'], 'number','max' => 1.0,'min' => 0],
            [['email','telephone','code'], 'unique','targetClass' => '\api\models\V1\Affiliate', 'message' => '此号码已经注册过了！','on'=>['create']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'code'=>'编码',
            'username' => '姓名',
            'email' => 'Email',
            'telephone' => '电话',
            'password' => '密码',
            'commission' => '佣金',
            'status' => '状态',
        ];
    }
    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function save()
    {
        if($this->validate()){
            if(!$model=$this->_user){
                $model=new Affiliate();
                $model->username=$this->username;
                $model->email=$this->email;
                $model->telephone=$this->telephone;
                $model->parent_id=Yii::$app->user->getId();
                $model->commission=$this->commission;
                $model->code=$this->code;
                $model->setPassword($this->password);
                $model->generateAuthKey();
                $model->ip=Yii::$app->request->getUserIP();
                $model->date_added=date('Y-m-d H:i:s',time());
                $model->status=$this->status;
                $model->save();
            }else{
                $model->username=$this->username;
                if($this->password){
                    $model->setPassword($this->password);
                }
                $model->commission=$this->commission;
                $model->status=$this->status;
                $model->date_added=date('Y-m-d H:i:s',time());
                $model->save();
            }
            return $model;
        }
        return null;
    }

}
