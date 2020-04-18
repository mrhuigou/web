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
            $return_data = array(
                'CODE'=>$model->code,
                'NAME'=>$model->username,
                'DISPLAY_NAME'=>$model->username,
                'TYPE'=> 'COMPANY',
                'MODE'=>'ON_LINE',
                'REBATE_TYPE'=>'PRODUCT',
//                'DESCRIPTION'=>$model->description,
                'CONTACT_NAME'=>$model->username,
                'TELEPHONE'=>$model->telephone,
            );
            //自动同步后台
            if($return_data){
                $erp_wsdl = Yii::$app->params['ERP_SOAP_URL'];
                $client = new \SoapClient($erp_wsdl, array('soap_version' => SOAP_1_1, 'exceptions' => false));
                $data=$this->getParam('applyAffiliate',array($return_data));
                $content = $client->getInterfaceForJson($data);
                $result=$this->getResult($content);
                Yii::error(json_encode($result));
                if($result['status']=='OK'){
                    $model->send_status = 1;
                    $model->save();
                }
            }
            return $model;
        }
        return null;
    }

    //生成请求数据方法
    protected function getParam($a,$d=array(),$v='1.0'){
        $t=time();
        $m='webservice';
        $key='asdf';
        $data=array('a'=>$a,'c'=>'NONE','d'=>$d,'f'=>'json','k'=>md5($t.$m.$key),'m'=>$m,'l'=>'CN','p'=>'soap','t'=>$t,'v'=>$v);
        return json_encode($data);
    }

    //获取结果数据方法
    protected function getResult($data){
        $result=json_decode($data,true);
        return $result;
    }
}
