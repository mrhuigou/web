<?php
namespace backend\models\form;
use api\models\V1\Order;
use api\models\V1\ReturnProduct;
use yii\base\Exception;
use yii\base\Model;
use Yii;
use api\models\V1\GroundPushPointToCustomer;
use api\models\V1\GroundPushStock;
use api\models\V1\OrderProduct;
use api\models\V1\ReturnBase;
use h5\models\ReturnAllForm;
use h5\models\ReturnForm;
use yii\db\StaleObjectException;

/**
 * AddressForm
 */
class ReturnGroundPushFrom extends Model {
    public $order_id;
    public $product_code;
    public $return_method = 'RETURN_PAY'; //默认退款方式  退货方式为：RETURN_GOODS


    public function __construct($order_id = 0,$product_code =0, $config = [])
    {
        if($order_id){
            $this->order_id = $order_id;
        }
        if($product_code){
            $this->product_code = $product_code;
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['product_code', 'order_id'], 'string'],
            [['return_method'],'safe']

        ];
    }
    public function save()
    {
        if ($this->validate()) {

                if ($this->order_id) {
                    $order = Order::find()->where(['order_id' => $this->order_id])->one();
                    if ($order) {
                        if ($this->product_code) {
                            $order_product = OrderProduct::findOne(['product_code' => $this->product_code, 'order_id' => $order->order_id]);
                            if (!$order_product) {
                                throw new Exception("订单商品与订单不匹配");
                            }
                        } else {
                            $order_product = '';
                        }
                        $point_to_customer = GroundPushPointToCustomer::findOne(['order_id' => $order->order_id]);
                        if ($point_to_customer && $point_to_customer->point && $point_to_customer->point->type == 'SAME') {
                            $return_base = ReturnBase::findOne(['order_id' => $order->order_id]);
                            if (!$return_base) {
                                if ($order_product) {
                                    $model = new ReturnForm(['order' => $order, 'order_product' => $order_product]);
                                    $order->order_status_id = 10; //部分退，已收货状态
                                } else {
                                    $model = new ReturnAllForm(['order' => $order]);
                                    $order->order_status_id = 4; //全退， 退货处理中状态
                                }
                                $order->save();
                                $this->returnFormActions($model,$order);
                                //地推订单库存操作
                                $this->returnGroundPushStock($order,$order_product);
                            } else {
                                if ($order_product) {
                                    $return_product = ReturnProduct::findOne(['return_id'=>$return_base->return_id,'product_code'=>$order_product->product_code]);
                                    if(!$return_product){
                                        $model = new ReturnForm(['order' => $order, 'order_product' => $order_product]);
                                        $order->order_status_id = 10; //部分退，已收货状态
                                        $order->save();
                                        $this->returnFormActions($model,$order); //生成退货单 并通知wms后台
                                        //地推订单库存操作
                                        $this->returnGroundPushStock($order,$order_product);
                                        return null;
                                    }
                                }

                                if ($return_base->send_status == 0) {
                                    if ($this->sendReturnGroundPush($return_base)) {
                                        $return_base->send_status = 1;
                                        $return_base->save();
                                    } else {
                                        $return_base->send_status = 0;
                                        $return_base->save();
                                    }
                                }
                            }
                        } else {
                            throw new Exception("非地推订单");
                        }
                    } else {
                        throw new Exception("订单信息不存在");
                    }
            }
        }
        return null;
    }
    //$model  ReturnForm（部分退货） 或者 ReturnAllForm（全退）
    //生成新的退货单 并通知wms后台
    private function returnFormActions($model,$order){
        $model->setReturnStatus(1);
        $model->return_model = $this->return_method ? $this->return_method : 'RETURN_PAY';
        $model->comment = '<地推订单自动退货>';
        $model->username = $order->firstname ? $order->firstname : '匿名';
        $model->telephone = $order->telephone;
        $model->paymethod = 1;
        $return_base = $model->submit();
        //同步后台
        if ($this->sendReturnGroundPush($return_base)) {
            $return_base->send_status = 1;
            $return_base->save();
        } else {
            $return_base->send_status = 0;
            $return_base->save();
        }
    }
    public function attributeLabels()
    {
        return ['type' => '类型',
            'sort_order' => '排序',
            'status' => '状态',
            'image' => '图片',
            'title' => '标题',
            'description'=>'内容',
            'meta_keyword'=>'关键词',
            'meta_description'=>'页面描述'
        ];
    }
    private function returnGroundPushStock($order,$_order_product = ''){
        if($order){
            $ground_push_point_to_customer = GroundPushPointToCustomer::findOne(['customer_id'=>$order->customer_id,'order_id'=>$order->order_id]);
            if($ground_push_point_to_customer){
                $point_id = $ground_push_point_to_customer->point_id;
                if($_order_product){ //部分退货
                    $order_product = $_order_product;
                    $fn = function ($point_id,$order_product) use (&$fn){
                        $ground_stock=GroundPushStock::findOne(['ground_push_point_id'=>$point_id,'product_code'=>$order_product->product_code]);
                        if($ground_stock){
                            try{
                                $ground_stock->tmp_qty = $ground_stock->tmp_qty-$order_product->quantity;
                                $ground_stock->last_time=date("Y-m-d H:i:s");
                                $ground_stock->save(false);
                            }catch (StaleObjectException $e){
                                $fn($point_id,$order_product);
                            }
                        }
                    };
                    $fn($point_id,$order_product);
                }else{ //全部退货
                    if($order->orderProducts){
                        foreach ($order->orderProducts as $order_product){
                            // $ground_push_stock = GroundPushStock::findOne(['product_code'=>$orderProduct->product_code,'ground_push_point_id'=>$point_id]);

                            $fn = function ($point_id,$order_product) use (&$fn){
                                $ground_stock=GroundPushStock::findOne(['ground_push_point_id'=>$point_id,'product_code'=>$order_product->product_code]);
                                if($ground_stock){
                                    try{
                                        $ground_stock->tmp_qty = $ground_stock->tmp_qty-$order_product->quantity;
                                        $ground_stock->last_time=date("Y-m-d H:i:s");
                                        $ground_stock->save(false);
                                    }catch (StaleObjectException $e){
                                        $fn($point_id,$order_product);
                                    }
                                }
                            };
                            $fn($point_id,$order_product);
                        }
                    }
                }

            }
        }
    }
    private function sendReturnGroundPush($return_model){
        // $return_id = $return_model->return_id;
        $return_info = $return_model;
        if($return_info){
            $order_info= Order::findOne($return_info->order_id);
            $orderAddress = \api\models\V1\OrderShipping::findOne(['order_id'=>$return_info->order_id]);
            $returnProducts = $return_info->returnProduct;
            $return_total=$return_info->totals;
            $return_product = [];
            $line_no=1;
            if($returnProducts){
                foreach($returnProducts as $key=>$returnproduct){

                    $return_product[]=[
                        'ORDERCODE'=>$return_info->return_code,
                        'TYPE' => 'product',
                        'LINENO'=>$line_no,
                        'SHOPCODE'=>$return_info->order->store->store_code,
                        'PRODUCTCODE'=>$returnproduct->product_base_code,
                        'PUCODE'=>$returnproduct->product_code,
                        'QUANTITY'=>$returnproduct->quantity,
                        'PRICE'=>bcdiv($returnproduct->total,$returnproduct->quantity,2),
                        'PAYMENT'=>$returnproduct->total,
                        'PROMOTIONDETAILCODE'=>'',
                        'AMOUNT'=>$returnproduct->total,
                        'DESCRIPTION'=>$returnproduct->comment?$returnproduct->comment:'',
                        'SCANS'=>[]
                    ];
                    $line_no++;
                }
            }
            if($return_total){
                foreach($return_total as $value){
                    $return_product[]=[
                        'ORDERCODE'=>$return_info->return_code,
                        'TYPE' => $value->code,
                        'LINENO'=>$line_no,
                        'SHOPCODE'=>$return_info->order->store->store_code,
                        'PRODUCTCODE'=>'',
                        'PUCODE'=>'',
                        'QUANTITY'=>'',
                        'PRICE'=>'',
                        'PAYMENT'=>'',
                        'PROMOTIONDETAILCODE'=>'',
                        'AMOUNT'=>$value->value,
                        'DESCRIPTION'=>$value->title,
                        'SCANS'=>[]
                    ];
                    $line_no++;
                }
            }
            $order_invoice=array('0'=>'不需要发票','1'=>'个人','2'=>'企业');

            $return_data=array(
                'CODE'=>$return_info->return_code,
                'PARENTCODE'=>'',
                'ORDERDATE'=>$return_info->date_added,
                'PLATFORMCODE'=>'PT0001',
                'STATUS'=>'ACTIVE',
                'BEDELIVERY'=>true,
                'SOURCE'=>'WEB',
                'TYPE'=>'RETURN',
                'RETURNTYPE'=> 'PART',
                'RETURN_DEAL_METHOD'=>strtoupper($return_info->return_method),
                'PAYTYPE'=>$order_info->payment_method,
                'PAYMENT'=>$return_info->total,
                'MEMBERCODE'=> $order_info->customer_id,
                //'SHIPPING'=> $shipping,
                'DELIVERY_STATION_CODE'=>'',
                'CONTACTNAME'=>$return_info->firstname, //$order_info['shipping_lastname'].$order_info['shipping_firstname'],
                'TELEPHONE'=>$return_info->telephone, //$order_info['shipping_telephone'],
                'MOBILE'=>$return_info->telephone, //$order_info['shipping_telephone'],
                'CITY'=> (isset($orderAddress->shipping_city)?$orderAddress->shipping_city:'').(isset($orderAddress->shipping_district)?$orderAddress->shipping_district:''), //$order_info['shipping_city'],
                'ADDRESS'=> (isset($orderAddress->shipping_district)?$orderAddress->shipping_district:'').(isset($orderAddress->shipping_address_1)?$orderAddress->shipping_address_1:''), //$order_info['shipping_address_1'],
                'DESCRIPTION'=>$return_info->comment?$return_info->comment:'',
                'RELATEDBILL1'=>$return_info->order_code,
                'RELATEDBILL2'=>'',
                'PAYTYPECODE'=> '',
                'DELIVERY_TYPE'=> '',
                'DELIVERY_VALUE'=> '',
                'SERIAL_NUMBER'=> '',
                'INVOICE_TYPE'=>array_search($order_info->invoice_temp,$order_invoice),
                'INVOICE_NAME'=>$order_info->invoice_title,
                'INVOICE_COMPANY'=>$order_info->invoice_title,
                'INVOICE_TAX_NUMBER'=>'', //$order_info->invoice_tax_number'],
                'INVOICE_ADD_TEL'=>'', //$order_info->invoice_add_tel'],
                'INVOICE_BANK'=>'', //$order_info->invoice_bank'],
                'DETAILS'=>$return_product,
                'SCANS'=>array(),
                'USEPOINTS'=>$order_info->use_points ? true : false,
            );
            if($return_data){
                $erp_wsdl = \Yii::$app->params['ERP_SOAP_URL'];
                $client = new \SoapClient($erp_wsdl, array('soap_version' => SOAP_1_1, 'exceptions' => false));
                $data=$this->getParam('createOrder',array($return_data));
                $content = $client->getInterfaceForJson($data);
                $result=$this->getResult($content);
                if($result['status']=='OK'){
                    $return_info->send_status = 1;
                    $return_info->save();
                    return true;
                }
            }
        }
        return false;
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
