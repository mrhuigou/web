<?php
namespace frontend\models;

use common\component\Helper\OrderSn;
use Yii;
use yii\base\Model;
use common\models\User;
use api\models\V1\Order;
use api\models\V1\OrderDigitalProduct;
use api\models\V1\OrderTotal;
use api\models\V1\CustomerTransaction;

/**
 * Login form
 */
class RechargeBalanceForm extends Model
{
    public $amount;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'required'],
            ['amount', 'integer','min'=>10,'max'=>5000],
        ];
    }


    public function attributeLabels(){
        return ['amount'=>'充值金额'];
    }

    public function save()
    {
	    if($this->validate()) {
		    $transaction = \Yii::$app->db->beginTransaction();
		    try {
			    $Order_model = new Order();
			    $Order_model->order_no = OrderSn::generateNumber();
			    $Order_model->order_type_code = 'recharge';
			    $Order_model->platform_id = 1;
			    $Order_model->platform_name = "智慧生活";
			    $Order_model->platform_url = Yii::$app->request->getHostInfo();
			    $Order_model->store_id = 1;
			    $Order_model->store_name = '青岛家润店';
			    $Order_model->store_url = '';
			    $Order_model->customer_group_id = Yii::$app->user->identity['customer_group_id'];
			    $Order_model->customer_id = Yii::$app->user->getId();
			    $Order_model->firstname = Yii::$app->user->identity['firstname'];
			    $Order_model->lastname = Yii::$app->user->identity['lastname'];
			    $Order_model->email = Yii::$app->user->identity['email'];
			    $Order_model->telephone = Yii::$app->user->identity['telephone'];
			    $Order_model->gender = Yii::$app->user->identity['gender'];
			    $Order_model->payment_method = "";
			    $Order_model->payment_code = "";
			    $Order_model->total = $this->amount;
			    $Order_model->comment = '';
			    $Order_model->order_status_id = 1;
			    $Order_model->affiliate_id = 0;
			    $Order_model->commission = 0;
			    $Order_model->ip = Yii::$app->request->userIP;
			    $Order_model->user_agent = Yii::$app->request->userAgent;
			    $Order_model->accept_language = Yii::$app->request->getPreferredLanguage();
			    $Order_model->date_added = date("Y-m-d H:i:s", time());
			    $Order_model->invoice_temp = "不需要发票";
			    $Order_model->invoice_title = Yii::$app->request->post("invoice_title");
			    $Order_model->invoice_prefix = 'INV-2014-00';
			    $Order_model->in_cod = 0;
			    $Order_model->sent_to_erp = "N";
			    if (!$Order_model->save(false)) {
				    throw new \Exception("订单数据异常");
			    }
			    $Order_digital = new OrderDigitalProduct();
			    $Order_digital->order_id = $Order_model->order_id;
			    $Order_digital->code = 0;
			    $Order_digital->type = 'account';
			    $Order_digital->name = '账户充值(￥'.$this->amount.')';
			    $Order_digital->account = \Yii::$app->user->getId();
			    $Order_digital->model = '账户充值';
			    $Order_digital->qty = 1;
			    $Order_digital->price = $this->amount;
			    $Order_digital->total = $this->amount;
			    $Order_digital->status = 0;
			    if (!$Order_digital->save(false)) {
				    throw new \Exception("订单商品数据异常");
			    }
			    $Order_total = new OrderTotal();
			    $Order_total->order_id = $Order_model->order_id;
			    $Order_total->code = 'total';
			    $Order_total->title = '订单总计';
			    $Order_total->text = '￥' . $this->amount;
			    $Order_total->value = $this->amount;
			    $Order_total->sort_order = 4;
			    if (!$Order_total->save(false)) {
				    throw new \Exception("订单小计异常");
			    }
			    $transaction->commit();
			    return $Order_model->order_no;
		    } catch (\Exception $e) {
			    $transaction->rollBack();
			    throw new \Exception("参数异常");
		    }
	    }else{
		 return false;
	    }
    }
}
