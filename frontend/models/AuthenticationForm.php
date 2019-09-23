<?php
namespace frontend\models;

use api\models\V1\Customer;
use api\models\V1\CustomerUmsauth;
use yii\base\Model;
use Yii;
use common\component\Helper\Helper;
use yii\web\NotFoundHttpException;

/**
 * Signup form
 */
class AuthenticationForm extends Model
{  
     public $name;
    public $cert;
    public $card;
    public $_user;

    public function __construct($config = [])
    {
        $this->_user=Customer::findOne(['customer_id'=>Yii::$app->user->identity->getId()]);

        if(!$this->_user || $this->_user->idcard_validate == 2){
           throw new NotFoundHttpException('用户已经认证过了.');
        }
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            [['name','cert','card'], 'required'],
            ['name', 'string', 'min' => 2],
            ['cert', 'string', 'min' => 15],
            ['cert', 'string', 'max' => 18],
            ['card','string', 'min' => 12,'max'=>19],
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {

            $tag = '365jiarun';
            $customer_id = \Yii::$app->user->getId();

           $data=array(
               'tag' => $tag,
               'name' => trim(json_encode($this->name),'"'),
               'cert' => $this->cert,
               'card' => $this->card,
           );

           $result = Helper::Sendums($data);

           if($result){
               if($result->result->respCode == '00'){
                   $ums_auth = new CustomerUmsauth();
                   $ums_auth->customer_id = $customer_id;
                   $ums_auth->idcard = $this->cert;
                   $ums_auth->union_card = $this->card;
                   $ums_auth->status = 1;
                   $ums_auth->date_added = date("Y-m-d H:i:s");
                   $ums_auth->save();
                   /*老版本的做法，兼容*/
                   $customer = Customer::find()->where(['customer_id'=>$customer_id])->one();
                   $customer->idcard = $this->cert;
                   $customer->idcard_validate = 2; //通过
                   $customer->save();
                   /*老版本的做法，兼容*/
                   return true;
               }
           }
        }
        Yii::$app->getSession()->setFlash('error', '您输入信息有误，请核对后再提交！');
        return false;
    }
    public function attributeLabels(){
        return [
        'name'=>'姓名',
        'cert'=>'身份证号',
        'card'=>'银行卡号',
        ];
    }
}
