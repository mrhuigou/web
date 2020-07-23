<?php

namespace backend\controllers;

use api\models\V1\CustomerAuthentication;
use api\models\V1\Order;
use api\models\V1\OrderGift;
use api\models\V1\OrderShipping;
use api\models\V1\ProductBaseDescription;
use api\models\V1\Promotion;
use api\models\V1\PromotionDetail;
use api\models\V1\ReturnTotal;
use common\component\Helper\Encrypt3des;
use Yii;
use api\models\V1\ReturnBase;
use api\models\V1\ReturnBaseSearch;
use api\models\V1\ReturnProduct;
use api\models\V1\OrderProduct;
use api\models\V1\ReturnHistory;
use yii\base\ErrorException;
use yii\httpclient\Client;
use yii\log\Logger;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * RefundController implements the CRUD actions for ReturnBase model.
 */
class RefundController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ReturnBase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReturnBaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReturnBase model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'products' => $this->findProducts($id),
            'history' => $this->findHistory($id),
        ]);
    }

    /**
     * Creates a new ReturnBase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReturnBase();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $lastId = ReturnBase::find()->max('return_id');
                $model->return_code = "RO" .$model->order_id.$lastId;
                $model->order_code = "O" . $model->order_id;
                $model->date_added = date('Y-m-d H:i:s');

                $model->save();
                $post_return_products = Yii::$app->request->post('orderProduct');
                $post_return_gifts = Yii::$app->request->post('orderGift');
                if($post_return_products){
                    foreach ($post_return_products as $post_return_product){
                        if(isset($post_return_product['order_product_id'])){
                            $order_product = OrderProduct::findOne(['order_product_id'=>$post_return_product['order_product_id']]);

                            $return_product_model = new ReturnProduct();
                            $return_product_model->order_product_id = $order_product->order_product_id;
                            $return_product_model->return_id = $model->return_id;
                            $return_product_model->product_base_id = $order_product->product_base_id;
                            $return_product_model->product_base_code = $order_product->product_base_code;

                            $return_product_model->product_id = $order_product->product_id;
                            $return_product_model->product_code = $order_product->product_code;
                            $return_product_model->model = $order_product->model;

                            $return_product_model->name = $order_product->name;
                            $return_product_model->quantity = $post_return_product['quantity'];
                            $return_product_model->total = $post_return_product['total'];
                            $return_product_model->unit = $order_product->unit;
                            $return_product_model->format = $order_product->format;
                            $return_product_model->from_table = 'order_product';
                            $return_product_model->from_id = $order_product->order_product_id;
                            $return_product_model->commission = $order_product->commission;
                            $return_product_model->save();
                        }
                    }
                }
                if($post_return_gifts){
                    foreach ($post_return_gifts as $post_return_gift){
                        if(isset($post_return_gift['order_product_id'])){
                            $order_gift = OrderGift::findOne(['order_gift_id'=>$post_return_gift['order_product_id']]);

                            $return_product_model = new ReturnProduct();
                            $return_product_model->order_product_id = $order_gift->order_gift_id;
                            $return_product_model->return_id = $model->return_id;
                            $return_product_model->product_base_id = $order_gift->product_base_id;
                            $return_product_model->product_base_code = $order_gift->product_base_code;

                            $return_product_model->product_id = $order_gift->product_id;
                            $return_product_model->product_code = $order_gift->product_code;
                            $return_product_model->model = 'gift';

                            $return_product_model->name = $order_gift->name;
                            $return_product_model->quantity = $post_return_gift['quantity'];
                            $return_product_model->total = $post_return_gift['total'];
                            $return_product_model->unit = $order_gift->unit;
                            $return_product_model->format = $order_gift->format;
                            $return_product_model->from_table = 'order_gift';
                            $return_product_model->from_id = $order_gift->order_gift_id;
                            $return_product_model->save();
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new NotFoundHttpException($e->getMessage().' line:'.$e->getLine().' code:'.$e->getCode());
            }

            return $this->redirect(['view', 'id' => $model->return_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionProductUpdate(){
		$return_product_id=Yii::$app->request->post('pk');
		$name=Yii::$app->request->post('name');
		$value=Yii::$app->request->post('value');
		if($model=ReturnProduct::findOne($return_product_id)){
			$model->{$name}=$value;
			$model->save();
			$this->resetReturnTotal($model->return_id);
		}
    }
    /**
     * Updates an existing ReturnBase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
	    $return_id=Yii::$app->request->post('pk');
	    $name=Yii::$app->request->post('name');
	    $value=Yii::$app->request->post('value');
	    if($model=ReturnBase::findOne($return_id)){
		    $model->{$name}=$value;
		    $model->save();
		    $this->resetReturnTotal($return_id);
	    }
    }
	private function resetReturnTotal($return_id){
			if($model=ReturnBase::findOne($return_id)){
               $total=0;
               if($model->returnProduct){
               	foreach ($model->returnProduct as $rp){
	                $total=$total+$rp->total;
                }
               }
               if($model->totals){
	               foreach ($model->totals as $val){
		               $total=bcadd($total,$val->value,2);
	               }
               }
               $model->total=$total;
               $model->save();
			}
	}
    public function actionAddrecord($id){
		$title=Yii::$app->request->post('title');
		$type=Yii::$app->request->post('type');
		$value=Yii::$app->request->post('value');
		$type_array=['sub_total'=>1,'shipping'=>2,'coupon'=>3,'other'=>4,'total'=>5];
		if($title && $type && $value){
            $model=new ReturnTotal();
            $model->return_id=$id;
            $model->title=$title;
            $model->code=$type;
            $model->text="￥".$value;
            $model->value=floatval($value);
            $model->sort_order=$type_array[$type];
            $model->save();
            $this->resetReturnTotal($id);
		}
    }
	public function actionDeleteRecord($id)
	{
		$return_total = ReturnTotal::findOne($id);
		if($return_total){
			$return_total->delete();
			$this->resetReturnTotal($return_total->return_id);
		}
		return $this->redirect(['view', 'id' => $return_total->return_id]);
	}
    /**
     * Deletes an existing ReturnBase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteproduct($id)
    {
        $return_product = ReturnProduct::findOne($id);
        if($return_product){
            $return_product->delete();
	        $this->resetReturnTotal($return_product->return_id);
        }
        return $this->redirect(['view', 'id' => $return_product->return_id]);
    }
	public function actionHistory($id){
		if($base_model=$this->findModel($id)){
			$model=new ReturnHistory();
			$model->return_id=$id;
			$model->return_status_id=Yii::$app->request->post('return_status_id');
			$model->comment=Yii::$app->request->post('comment');
			$model->notify=0;
			$model->date_added=date('Y-m-d H:i:s',time());
			$model->save();
			$base_model->return_status_id=Yii::$app->request->post('return_status_id');
			$base_model->date_modified=date('Y-m-d H:i:s',time());
			$base_model->save();
		}
	}
    /**
     * Finds the ReturnBase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReturnBase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReturnBase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findProducts($id)
    {
        $products = new ActiveDataProvider([
            'query' => ReturnProduct::find()->where(['return_id'=>$id]),
        ]);
        if ($products  !== null) {
            return $products;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }

    protected function findOrderProducts($id)
    {
        $products = OrderProduct::find()->where(['order_id'=>$id])->all();
        if ($products  !== null) {
            return $products;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }

    protected function findHistory($id)
    {
        $history = new ActiveDataProvider([
            'query' => ReturnHistory::find()->where(['return_id'=>$id]),
        ]);
        if ($history  !== null) {
            return $history;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }
    public function actionSend(){
        $return_id = Yii::$app->request->get("id");
        $return_info = ReturnBase::findOne($return_id);
        if($return_info){
            $order_info= Order::findOne($return_info->order_id);
            $orderAddress = OrderShipping::findOne(['order_id'=>$return_info->order_id]);
            $returnProducts = $return_info->returnProduct;
			$return_total=$return_info->totals;
            $return_product = [];
	        $line_no=1;
	        if($returnProducts){
		        foreach($returnProducts as $key=>$returnproduct){
			        if($returnproduct->from_table == 'order_product'){
				        $od_product = OrderProduct::findOne($returnproduct->from_id);
			        }elseif ($returnproduct['from_table'] == 'order_gift') {
				        $od_product = OrderGift::findOne($returnproduct->from_id);
			        }
			        $spCode=$returnproduct->product->store_code;
			        $return_product[]=[
				        'ORDERCODE'=>$return_info->return_code,
                        'TYPE' => 'product',
				        'LINENO'=>$line_no,
				        'SHOPCODE'=>$spCode?$spCode:'',
				        'PRODUCTCODE'=>$returnproduct->product_base_code,
				        'PUCODE'=>$returnproduct->product_code,
				        'QUANTITY'=>$returnproduct->quantity,
				        'PRICE'=>bcdiv($returnproduct->total,$returnproduct->quantity,2),
				        'PAYMENT'=>$returnproduct->total,
				        'PROMOTIONDETAILCODE'=>'',
				        'AMOUNT'=> $od_product ? $od_product->total : $returnproduct->total,
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
//订单发票信息
            $order_invoice = ['0' => '不需要发票', '1' => '个人发票','2'=>'企业增值税专票', '3' => '企业增值税普票'];
            $invoice_type = array_search($order_info->invoice_temp, $order_invoice);

            if(strpos($order_info->invoice_title,"|")){
                if($invoice_type == 2){
                    list($a,$b,$c,$d)=explode("|",$order_info->invoice_title);
                }else{
                    list($a,$b)=explode("|",$order_info->invoice_title);
                    $c = '--';
                    $d = '--';
                }
            }else{
                if($invoice_type == 1){
                    $a = $order_info->invoice_title;
                    $b = '--';
                    $c = '--';
                    $d = '--';
                }else{
                    list($a,$b,$c,$d)=["---","---","---","---"];
                }
            }
            $order_invoice_data = [
                'INVOICE_TYPE' => $invoice_type,
                'INVOICE_NAME' => $a,
                'INVOICE_COMPANY' => $a,
                'INVOICE_TAX_NUMBER' =>$b,
                'INVOICE_ADD_TEL' => $c,
                'INVOICE_BANK' => $d,
            ];
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
                'INVOICE_TYPE'=> $invoice_type,
                'INVOICE_NAME'=>$a,
                'INVOICE_COMPANY'=>$a,
                'INVOICE_TAX_NUMBER'=>$b, //$order_info->invoice_tax_number'],
                'INVOICE_ADD_TEL'=>$c, //$order_info->invoice_add_tel'],
                'INVOICE_BANK'=>$d, //$order_info->invoice_bank'],
                'DETAILS'=>$return_product,
                'SCANS'=>array(),
                'USEPOINTS'=>$order_info->use_points ? true : false,
            );
            if($return_data){
                $erp_wsdl = Yii::$app->params['ERP_SOAP_URL'];
                $client = new \SoapClient($erp_wsdl, array('soap_version' => SOAP_1_1, 'exceptions' => false));
                $data=$this->getParam('createOrder',array($return_data));
                $content = $client->getInterfaceForJson($data);
                $result=$this->getResult($content);
                Yii::error(json_encode($result));
                if($result['status']=='OK'){
                    $return_info->send_status = 1;
                    $return_info->save();
                    $this->sendToZhqd($return_info);
                }
            }
        }
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
    private function sendToZhqd($return){
        //同步订单状态到 智慧青岛
        $order = $return->order;
        try{
            if($order && $order->affiliate_id == 259 && ($order->order_type_code == 'normal' || $order->order_type_code == 'presell')){
                $return_products = $return->returnProduct;
                $list = [];
                if($return_products){
                    foreach ($return_products as $return_product){
                        $list[] = [
                            'name'=>$return_product->name,
                            'quantity'=>$return_product->quantity,
                            'price' => round($return_product->total/$return_product->quantity * 100,2),
                            'total'=> $return_product->total*100,
                        ];
                    }
                }
                $getUrl = 'http://0532.qingdaonews.com/function/life/marketorder';
                $sign_time = time();
                $key = 'ziuppmmve4sb0sv94omuhk1400z95w5d';
                $iv = md5($key . $sign_time);
                $iv = substr($iv, 0, 8);
                $customer_auth = CustomerAuthentication::findOne(['provider' => 'Zhqd', 'customer_id' => $order->customer_id]);
                $token = "";
                if($customer_auth){
                    $token = $customer_auth->identifier;  //本站提供的用户token
                }
                $encrypt = new Encrypt3des();
               // $sign = urlencode($encrypt->encode("token=" . $token, $key, $iv));
                $sign = urlencode($encrypt->encode("token=" . $token."&mobile=" . $order->telephone, $key, $iv));
                $parms = [
                    'sign_time' => $sign_time,
                    'sign'  => $sign,
                    'action'=> 'return',
                    'order_id' => $order->order_no,
                    'fee' => $return->total,
                    'list'=>json_encode($list)
                ];

                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('get')
                    ->setUrl($getUrl)
                    ->setData($parms)
                    ->send();
                Yii::getLogger()->log(json_encode($response), Logger::LEVEL_TRACE);
            }
        }catch (ErrorException $e){
            Yii::getLogger()->log(json_encode($e), Logger::LEVEL_ERROR);
            return ;
        }
    }
    public function actionReturnAutoSelect(){
        //后台客服创建退货单时 选择订单id时候的操作
        $order_id = Yii::$app->request->post("order_id");
        $order = Order::findOne(['order_id'=>$order_id]);
        $return = [];
        if($order){
            $return['status'] = true;
            $return['order_no'] = $order->order_no;
            $return['customer_id'] = $order->customer_id;
            $return['firstname'] = $order->firstname;
            $return['telephone'] = $order->telephone;
            $return['date_ordered'] = $order->date_added;
            $html = "";
            if($order->orderProducts){
                foreach ($order->orderProducts as $orderProduct){
                    $html .= "<tr>";
                    $html .=  "<td><input type='checkbox' name='orderProduct[$orderProduct->order_product_id][order_product_id]' value='".$orderProduct->order_product_id."' /></td>";
                    $html .=  "<td> ". $orderProduct->name ."</td>" ;
                    $html .= "<td>".$orderProduct->product_code."<br>".$orderProduct->format ."--".$orderProduct->unit." </td>";
                    $html .= "<td>数量：<input type='input' name='orderProduct[$orderProduct->order_product_id][quantity]' value='".$orderProduct->quantity."' /></td>";
                    $html .= "<td>实付金额：<input type='input' name='orderProduct[$orderProduct->order_product_id][total]' value='".$orderProduct->pay_total."' /></td>";
                    $html .= "</tr>";
                }
            }
            if($order->allOrderGifts){
                foreach ($order->allOrderGifts as $orderGift){
                    $promotion_name = '';
                    if($orderGift->promotion_id){
                        $promotionDetail = PromotionDetail::findOne(['promotion_detail_id'=>$orderGift->promotion_id]);
                        if($promotionDetail){
                            $promotion_name = $promotionDetail->promotion->name;
                        }
                    }
                    $html .= "<tr>";
                    $html .=  "<td><input type='checkbox' name='orderGift[$orderGift->order_gift_id][order_product_id]' value='".$orderGift->order_gift_id."' /></td>";
                    $html .=  "<td>".$orderGift->name."<br>". $promotion_name ."【赠品】</td>" ;
                    $html .= "<td>".$orderGift->product_code."<br>".$orderGift->format ."--".$orderGift->unit." </td>";
                    $html .= "<td>数量：<input type='input' name='orderGift[$orderGift->order_gift_id][quantity]' value='".$orderGift->quantity."' /></td>";
                    $html .= "<td>实付金额：<input type='input' readonly='true' name='orderGift[$orderGift->order_gift_id][total]' value='".$orderGift->price."' /></td>";
                    $html .= "</tr>";
                }
            }
            $return['orderProducts'] = $html;
        }else{
            $return['status'] = false;
            $return['message'] = '订单信息不存在';
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $return;
    }
    public function actionAutoComplete(){
        $data=[];
        if($query=Yii::$app->request->get('term')){

            if($product_data=ProductBaseDescription::find()->where(['like','name',$query])->orWhere(['like','product_base_code',$query])->orderBy('product_base_id asc')->limit(10)->all()){
                foreach($product_data as $value){
                    $data[]=[
                        'value'=>"product|".$value->product_base_code,
                        'label'=>$value->name."|".$value->product_base_code,
                    ];
                }
            }

        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
}
