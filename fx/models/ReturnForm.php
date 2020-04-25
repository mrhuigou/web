<?php
namespace fx\models;
use api\models\V1\ReturnBase;
use api\models\V1\ReturnProduct;
use common\component\Helper\OrderSn;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class ReturnForm extends Model
{
    public $telephone;
    public $username;
    public $qty;
    public $total;
    public $comment;
//    public $paymethod=0;
    public $paymethod=1;
    public $return_type;
	public $return_model="RETURN_GOODS";
    public $order;
    public $order_product;
    private $return_status;
    public function __construct($data, $config = [])
    {
        if (isset($data['order'])) {
            $this->order = $data['order'];
            $this->username= $this->order->firstname;
            $this->telephone= $this->order->telephone;
            $this->return_status = 1;
        } else {
            throw new InvalidParamException("数据错误");
        }
        if (isset($data['order_product'])) {
            $this->order_product = $data['order_product'];
            $this->qty=max(0,$this->order_product->quantity - $this->order_product->getRefundQty());
        } else {
            throw new InvalidParamException("数据错误");
        }
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        ['return_model', 'required'],
            ['telephone', 'filter', 'filter' => 'trim'],
            ['telephone', 'required'],
            ['telephone', 'string', 'length' => 11],
            ['username', 'required'],
            ['username', 'string', 'min' => 2],
            ['comment', 'required'],
            ['comment', 'string','min'=>2],
            ['qty', 'required'],
            ['qty', 'number','integerOnly'=>true,'min'=>1],
            ['qty', 'CheckQty'],
            ['paymethod', 'required'],
        ];
    }

    public function CheckQty($attribute){
        if($this->qty<=0){
            $this->addError($attribute,'退货数量必须大于0');
        }
        if($this->qty>max(0,$this->order_product->quantity-$this->order_product->getRefundQty())){
            $this->addError($attribute,'超过购买数量');
        }
    }
    public function setReturnStatus($return_status_id=1){
        $this->return_status = $return_status_id;
    }
    public function submit()
    {
        if ($this->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
            	$lastId=ReturnBase::find()->max('return_id');
                $return = new ReturnBase();
                $return->return_code = "RO" .$this->order->order_id.$lastId;
                $return->order_id = $this->order->order_id;
                $return->order_code = "O" . $this->order->order_id;
                $return->order_no = $this->order->order_no;
                $return->date_ordered = $this->order->date_added;
                $return->customer_id = Yii::$app->user->getId();
                $return->firstname = $this->username;
                $return->email = $this->order->email;
                $return->telephone = $this->telephone;
	            $return->return_method=$this->return_model;
	            $return->return_status_id = $this->return_status ? $this->return_status : 1;
                $return->comment = $this->comment;
                $return->is_all_return = 0;
                $return->date_added = date("Y-m-d H:i:s");
                $return->date_modified = date("Y-m-d H:i:s");
                $return->send_status = 0;
                if(!$return->save(false)){
                    throw new \Exception(json_encode($return->errors));
                }
                $this->ReturnOrderGift($this->order,$return);
                $return_product = new ReturnProduct();
                $return_product->return_id = $return->return_id;
                $return_product->product_base_id = $this->order_product->product_base_id;
                $return_product->product_base_code = $this->order_product->product_base_code;
                $return_product->product_id = $this->order_product->product_id;
                $return_product->product_code = $this->order_product->product_code;
                $return_product->model = $this->order_product->model;
                $return_product->name = $this->order_product->name;
                $return_product->quantity = $this->qty;
                $price = bcdiv(abs($this->order_product->pay_total), $this->order_product->quantity, 10);
                $return_product->total = round(bcmul($price, $this->qty, 10), 2);
                $return_product->unit = $this->order_product->unit;
                $return_product->format = $this->order_product->format;
                $return_product->from_table = 'order_product';
                $return_product->from_id = $this->order_product->order_product_id;
                if($this->order_product->commission){
	                $return_product->commission=round(bcmul(bcdiv($this->order_product->commission,$this->order_product->quantity,10),$this->qty,10),4);
                }
                if(!$return_product->save(false)){
                    throw new \Exception(json_encode($return_product->errors));
                }
                $return->total = $return_product->total;
                $return->save();
                $this->order_product->refund_qty =  $this->order_product->refund_qty + $this->qty;
                $this->order_product->save();
                $this->ReturnProductGift($this->order_product,$return_product);
                $transaction->commit();
                return $return;

            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new \Exception($e->getMessage());
            }
        }
        return false;
    }
    public function attributeLabels(){
        return [
	        'return_model'=>'售后类型',
            'username' => '联系人',
            'telephone'=>'手机号码',
            'comment'=>'问题描述',
            'qty'=>'数量',
            'paymethod' => '退款方式'
        ];
    }
    public function ReturnProductGift($order_product,$return_product){
        if($order_product && $order_product->gift){
            foreach($order_product->gift as $gift){
                $qty=max(0,$gift->quantity-$gift->getRefundQty());
                if($qty>0){
                    $model = new ReturnProduct();
                    $model->return_id = $return_product->return_id;
                    $model->order_product_id=$return_product->order_product_id;
                    $model->product_base_id = $gift->product_base_id;
                    $model->product_base_code = $gift->product_base_code;
                    $model->product_id = $gift->product_id;
                    $model->product_code = $gift->product_code;
                    $model->model = $gift->model;
                    $model->name = $gift->name;
                    $model->sku_name = $gift->sku_name;
                    $model->quantity = $qty;
                    $model->total =$gift->total;
                    $model->unit = $gift->unit;
                    $model->format = $gift->format;
                    $model->from_table = 'order_gift';
                    $model->from_id = $gift->order_gift_id;
                    if(!$model->save(false)){
                        throw new \Exception(json_encode($model->errors));
                    }
                    $gift->refund_qty= $gift->refund_qty + $qty;
                    $gift->save();
                }

            }
        }
    }
    public function ReturnOrderGift($order,$return){
        if($order->orderGifts){
            foreach($order->orderGifts as $gift){
                $qty=max(0,$gift->quantity-$gift->getRefundQty());
                if($qty>0){
                $model = new ReturnProduct();
                $model->return_id = $return->return_id;
                $model->order_product_id=0;
                $model->product_base_id = $gift->product_base_id;
                $model->product_base_code = $gift->product_base_code;
                $model->product_id = $gift->product_id;
                $model->product_code = $gift->product_code;
                $model->model = $gift->model;
                $model->name = $gift->name;
                $model->sku_name = $gift->sku_name;
                $model->quantity = $gift->quantity;
                $model->total =$gift->total;
                $model->unit = $gift->unit;
                $model->format = $gift->format;
                $model->from_table = 'order_gift';
                $model->from_id = $gift->order_gift_id;
                if(!$model->save(false)){
                    throw new \Exception(json_encode($model->errors));
                }
                $gift->refund_qty= $qty;
                $gift->save();
                }
            }
        }
    }
}
