<?php

namespace h5\controllers;

use api\models\V1\AffiliatePersonal;
use api\models\V1\CouponHistory;
use api\models\V1\CustomerChest;
use api\models\V1\CustomerCommission;
use api\models\V1\CustomerCommissionFlow;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerTransaction;
use api\models\V1\GroundPushPointToCustomer;
use api\models\V1\GroundPushStock;
use api\models\V1\Order;
use api\models\V1\OrderBlack;
use api\models\V1\OrderDeliveryComment;
use api\models\V1\OrderField;
use api\models\V1\OrderMerge;
use api\models\V1\OrderProduct;
use api\models\V1\OrderTotal;
use api\models\V1\PointCustomerFlow;
use api\models\V1\PromotionHistory;
use api\models\V1\ReturnBase;
use api\models\V1\ReturnProduct;
use api\models\V1\WarehouseLog;
use api\models\V1\WarehouseStock;
use common\component\Curl\Curl;
use common\component\Helper\OrderSn;
use common\component\Helper\Xcrypt;
use h5\models\ReturnAllForm;
use h5\models\ReturnForm;
use h5\widgets\Order\OrderShipping;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $url = Url::to(['/site/login','redirect'=>'/order/index?order_status='.\Yii::$app->request->get("order_status")]);
        if (\Yii::$app->user->isGuest) {

            return $this->redirect($url);
            //return $this->redirect(['/site/login']);
        }
        $order_status = \Yii::$app->request->get("order_status");
        if(strtoupper($order_status) == "NOPAY"){
            $order_status_id = 1;
        }elseif(strtoupper($order_status) == "PAYED"){
            $order_status_id =[2,3,5];
        }elseif(strtoupper($order_status) == "ONWAY"){
            $order_status_id = 9;
        }
        if(isset($order_status_id)){
            $where = array(
                'customer_id'=>\Yii::$app->user->getId(),
                'order_status_id' => $order_status_id,
            );
        }else{
            $where = array(
                'customer_id'=>\Yii::$app->user->getId(),
            );
        }
        $model =Order::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->where($where)->orderBy('date_added DESC'),
            'pagination' => [
                'pagesize' => '4',
            ]
        ]);


        return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);

    }
    public function actionReturn(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model = new ReturnBase();
	    $order_status=\Yii::$app->request->get('order_status',1);
        switch ($order_status){
	        case 1:
	        	$return_model='RETURN_GOODS';
	        	break;
	        case 2:
		        $return_model='RETURN_PAY';
		        break;
	        default:
	        	$return_model='RESHIP';
	        	break;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where(['customer_id'=>\Yii::$app->user->getId()])->andWhere(['return_method'=>$return_model])->orderBy('date_added DESC'),
            'pagination' => [
                'pagesize' => '4',
            ]
        ]);
        return $this->render('return',['dataProvider'=>$dataProvider,'model'=>$model,'order_status'=>$order_status]);
    }
    public function actionRefund(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $order_no = \Yii::$app->request->get("order_no");
        $item_id=\Yii::$app->request->get("item_id");
        if(!$order = Order::findOne(['order_no'=>$order_no,'customer_id'=>\Yii::$app->user->getId(),'order_status_id'=>[2,3,5,9,10,13]])){
            throw new NotFoundHttpException("没有找到相应订单");
        }
        if(!$order_product=OrderProduct::findOne(['order_product_id'=>$item_id,'order_id'=>$order->order_id])){
            throw new NotFoundHttpException("没有找到相应订单商品");
        }
        if($order_product->quantity<=$order_product->getRefundQty()){
            return $this->redirect('/order/return');
        }

        $model = new ReturnForm(['order'=>$order,'order_product'=>$order_product]);
        if ($model->load(\Yii::$app->request->post()) && $model->submit()) {
            return $this->redirect('/order/return');
        }

        return $this->render('refund',['model'=>$model,'order'=>$order,'order_product'=>$order_product]);
    }
    public function actionRefundAll(){
        if (\Yii::$app->user->isGuest) {
	        return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
        $order_no = \Yii::$app->request->get("order_no");
        if(!$order = Order::findOne(['order_no'=>$order_no,'customer_id'=>\Yii::$app->user->getId(),'order_status_id'=>[2,3,5,9,10,13]])){
            throw new NotFoundHttpException("没有找到相应订单");
        }
        if($order->getOrderRefundQty()>=$order->getOrderProductQty()){
            return $this->redirect('/order/return');
        }
        $model = new ReturnAllForm(['order'=>$order]);
        if ($model->load(\Yii::$app->request->post()) && $model->submit()) {
            return $this->redirect('/order/return');
        }
        return $this->render('refund-all',['model'=>$model,'order'=>$order]);
    }

    public function actionInfo(){
        if (\Yii::$app->user->isGuest) {
	        return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
        $order_no = \Yii::$app->request->get("order_no");
        $model = Order::findOne(['order_no'=>$order_no,'customer_id'=> \Yii::$app->user->getId()]);


        if($model) {
            return $this->render('info', ['model' => $model]);
        }else{
            throw new NotFoundHttpException("没有找到相应页面");
        }
    }
    public function actionShipping(){
        if (\Yii::$app->user->isGuest) {
	        return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
        $order_no = \Yii::$app->request->get("order_no");
        if($model = Order::findOne(['order_no'=>$order_no,'customer_id'=> \Yii::$app->user->getId()])){
            $curl=new Curl();
            $pass_path=$curl->get(\Yii::$app->params['TMS_API'].'/mrhgTmsServer/getCoorByOrderCode.action',['orderCode'=>'O'.$model->order_id]);
            $order_data=$curl->get(\Yii::$app->params['TMS_API'].'/mrhgTmsServer/getLocationsByOrderCode.action',['orderCode'=>'O'.$model->order_id]);

            if(($data=Json::decode($order_data)) && $data['success']==false){
                $data=[];
            }
            return $this->render('shipping', ['model' => $model,'order_path'=>$data,'pass_path'=>Json::decode($pass_path)]);
        }else{
            throw new NotFoundHttpException("没有找到相应页面");
        }
    }
    public function actionDelivery($order_no){
	    if (\Yii::$app->user->isGuest) {
		    return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
	    }
	    if(!$order_model=Order::findOne(['order_no'=>$order_no,'customer_id'=>\Yii::$app->user->getId()])){
		    throw  new NotFoundHttpException("订单不存在");
	    }
	    if($model=OrderDeliveryComment::findOne(['order_id'=>$order_model->order_id])){
			return $this->redirect('/order/index');
	    }
		return $this->render('delivery',['order_model'=>$order_model]);
    }
    public function actionDeliverySubmit($order_no){
    	try{
    		if(!$order_model=Order::findOne(['order_no'=>$order_no,'customer_id'=>\Yii::$app->user->getId()])){
    			throw  new ErrorException("订单不存在");
		    }
			if(!$score=\Yii::$app->request->post('score')){
				throw  new ErrorException("你还没有打分呢~");
			}
			$tags=\Yii::$app->request->post('tags',[]);
			$comment=\Yii::$app->request->post('comment');
			if(!$model=OrderDeliveryComment::findOne(['order_id'=>$order_model->order_id])){
				$model=new OrderDeliveryComment();
				$model->order_id=$order_model->order_id;
				$model->score=$score;
				$model->comment=$comment;
				$model->tags=Json::encode($tags);
				$model->created_at=time();
				$model->customer_id=\Yii::$app->user->getId();
				$model->save();
			}
		    $data = ['status' => 1, 'message' =>''];
	    } catch (ErrorException $e) {
		    $data = ['status' => 0, 'message' => $e->getMessage()];
	    }
	    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    return $data;
	}
    public function actionPay(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
        }
        $order_no=\Yii::$app->request->get('order_no');
        if($model=Order::findOne(['order_no'=>$order_no,'order_status_id'=>1,'customer_id'=>\Yii::$app->user->getId()])){
            if(!$tradeModel=OrderMerge::findOne(['order_ids'=>strval($model->order_id),'status'=>0])){
                $tradeModel=new OrderMerge();
                $tradeModel->merge_code=OrderSn::generateNumber();
                $tradeModel->order_ids=$model->order_id;
                $tradeModel->total=$model->total;
                $tradeModel->customer_id=\Yii::$app->user->getId();
                $tradeModel->status=0;
                $tradeModel->date_added=date("Y-m-d H:i:s");
                $tradeModel->date_modified=date("Y-m-d H:i:s");
                $tradeModel->save();
            }
            return $this->redirect(Url::to(['/payment/index','trade_no'=>$tradeModel->merge_code,'showwxpaytitle'=>1],true));
        }else{
            throw new NotFoundHttpException("订单已过期！");
        }
    }
    public function actionCancel(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $order_no = \Yii::$app->request->get("order_no");
        if($model = Order::findOne(['order_no'=>$order_no,'customer_id'=> \Yii::$app->user->getId()])){
            $this->returnOrderOccupation($model->order_id);
            $model->order_status_id=7;
            $model->save();
            return $this->redirect(['/order/info','order_no'=>$order_no]);
        }else{
            throw new NotFoundHttpException("没有找到相应页面");
        }
    }
    public function actionAddCart(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $order_no = \Yii::$app->request->get("order_no");
        if($model = Order::findOne(['order_no'=>$order_no,'customer_id'=> \Yii::$app->user->getId(),'order_type_code'=>['normal','presell']])){
            if($model->orderProducts){
                foreach($model->orderProducts as $order_product){
                    if($order_product->product){
                        \Yii::$app->cart->put($order_product->product->getCartPosition(),$order_product->quantity);
                    }
                }
            }else{
                throw new NotFoundHttpException("没有找到相应商品");
            }
            return $this->redirect('/cart/index');
        }else{
            throw new NotFoundHttpException("没有找到相应订单商品");
        }
    }

    protected function returnOrderOccupation($order_id){
        //返还宝物
        $this->returnOrderChest($order_id);
        //返还优惠券
        $order_totals = OrderTotal::find()->where(['code'=>'coupon','order_id'=>$order_id])->all();
        if($order_totals){
            foreach($order_totals as $order_total){
                if($customer_coupon = CustomerCoupon::findOne(['customer_coupon_id'=> $order_total->customer_code_id,'is_use'=>1])){
                    $customer_coupon->is_use = 0;
                    $customer_coupon->date_used = '';
                    $customer_coupon->save();
                }
            }
            CouponHistory::deleteAll(['order_id'=>$order_id]);
        }
        PromotionHistory::updateAll(['status'=>0],['order_id'=>$order_id]);
        OrderBlack::deleteAll(['order_id'=>$order_id]);
        if($model=Order::findOne(['order_id'=>$order_id])){
            if($model->orderProducts){
                foreach($model->orderProducts as $order_product){
                    if(strtolower($model->order_type_code) == 'groundpush'){
                        $point_to_customer = GroundPushPointToCustomer::findOne(['order_id'=>$model->order_id]);
                        if($point_to_customer){
                            $stock = GroundPushStock::findOne(['ground_push_point_id'=>$point_to_customer->point_id,'product_code'=>$order_product->product_code]);
                            $stock->tmp_qty = max(0,$stock->tmp_qty - $order_product->quantity);
                            $stock->last_time = date('Y-m-d H:i:s');
                            $stock->save();
                        }

                    }else{
                        if($m=WarehouseStock::findOne(['product_code'=>$order_product->product_code])){
                            $m->tmp_qty= max(0,$m->tmp_qty - $order_product->quantity);
                            $m->save();
//	                    $log=new WarehouseLog();
//	                    $log->type='cancel_order_product_h5';
//	                    $log->product_code=$m->product_code;
//	                    $log->qty=intval($m->quantity);
//	                    $log->create_time=time();
//	                    $log->save();
                        }
                        if($order_product->gift){
                            foreach($order_product->gift as $order_product_gift){
                                if($m=WarehouseStock::findOne(['product_code'=>$order_product_gift->product_code])){
                                    $m->tmp_qty= max(0,$m->tmp_qty - $order_product_gift->quantity);
                                    $m->save();
//	                            $log=new WarehouseLog();
//	                            $log->type='cancel_order_product_gift_h5';
//	                            $log->product_code=$m->product_code;
//	                            $log->qty=intval($m->quantity);
//	                            $log->create_time=time();
//	                            $log->save();
                                }
                            }
                        }
                    }

                }
            }
            if($model->orderGifts){
                foreach($model->orderGifts as $order_product){
                    if($m=WarehouseStock::findOne(['product_code'=>$order_product->product_code])){
                        $m->tmp_qty= max(0,$m->tmp_qty - $order_product->quantity);
                        $m->save();
//	                    $log=new WarehouseLog();
//	                    $log->type='cancel_order_gift_h5';
//	                    $log->product_code=$m->product_code;
//	                    $log->qty=intval($m->quantity);
//	                    $log->create_time=time();
//	                    $log->save();
                    }
                }
            }
            //$this->points_notice($model);//支付成功时候才会通知扣减积分，顾取消订单时候不予返还积分，积分返还应该仅在退货时候(尚融网贷积分不返还)
        }
        return true;
    }
    private function points_notice($order){
        try{
            if($order->use_points){
                $point_customer_flows = PointCustomerFlow::find()->where(['type'=>'order','type_id'=>$order->order_id])->all();
                if($point_customer_flows){
                    foreach ($point_customer_flows as $point_customer_flow){
                        $point_model = $point_customer_flow->pointCustomer->point;
                        $data['telephone'] = $order->telephone;
                        $data['changeType'] = 1; //1增加 2扣除
                        $data['description'] = '订单取消';
                        $data['orderId'] = $order->order_id;
                        $data['count'] = 1;
                        $data['status'] = 0;
                        $data['creditValue'] = $point_customer_flow->amount;
                        $data['changeDate'] = date('Y-m-d H:i:s');
                        $data['changeResource'] = 6;
                        $data['point_customer_flow_id'] = $point_customer_flow->point_customer_flow_id;
                        $point_model->notice($data);
                    }
                }
            }
        }catch (ErrorException $e){
            \Yii::error("points_notice=============>".$e->getMessage().'at'.$e->getLine());
        }

    }
    protected function returnOrderChest($order_id){
        $chests = CustomerChest::find()->where(['customer_id'=> \Yii::$app->user->getId(),'order_id'=>$order_id,'status'=>1])->all();
        if($chests) {
            foreach ($chests as $ch) {
                $ch->status = 0;
                $ch->order_id = 0;
                $ch->order_no = '';
                $ch->save();
            }
        }
        return true;
    }
    private function getCurrentCommission($order_model){
        $commission = 0;
        $in_order_products_array = [];
        $order_products_array = OrderProduct::find()->where(['order_id'=>$order_model->order_id])->asArray()->all();

        if($order_products_array){
            $returnBase = ReturnBase::find()->select('return_id')->where(['order_id'=>$order_model->order_id,'return_status_id'=>['1','2','3','4','5']]);
            $return_products_array = ReturnProduct::find()->where(['return_id'=>$returnBase])->asArray()->all();
            foreach ($order_products_array as $key => $value){
                if(!empty($return_products_array)){
                    $tmp_key = array_search($value->product_id, array_column($return_products_array, 'product_id'));
                    if( $tmp_key !==false ){ //已经退货的商品需要从数组中删除
                        unset($order_products_array[$key]);
                    }
                }
            }
        }
        $order_products_array = array_values($order_products_array);
        $aff_personals = AffiliatePersonal::find()->where(['status'=>1])->andWhere(['and','date_start <"'.$order_model->date_added.'"','date_end > "'.$order_model->date_added.'"'])->all();
        if($aff_personals ){
            foreach ($aff_personals as $aff_personal){
                if($aff_personal->details){
                    foreach ($aff_personal->details as $detail){
                        if(!empty($order_products_array)){
                            $order_products_array = array_values($order_products_array);
                            $key = array_search($detail->product_id, array_column($order_products_array, 'product_id'));
                            if( $key !==false ){
                                //删除该orderProduct 并记录Personal里提成值
                                $commission += $detail->commissionTotal * $order_products_array[$key]['quantity'];
                                //删除该orderProduct
                                $in_order_products_array[] = $detail->product_id;
                                unset($order_products_array[$key]);
                            }
                        }

                    }
                }
            }
        }
        $data['left_order_products_array'] = $order_products_array;//下一轮普通合伙人提成提成
        $data['in_order_products_array'] = $in_order_products_array;//AffiliatePersonal提成商品
        $data['current_commission'] = $commission;//AffiliatePersonal提成佣金
        return $data;
    }
    private function getCommision($order_model,$order_products_array=[]){
        $commission = 0;
        if($order_products_array){
            $order_products=OrderProduct::find()->where(['order_id'=>$order_model->order_id,'product_id'=> array_column($order_products_array, 'product_id')])->all();
        }else{
            $order_products=OrderProduct::find()->where(['order_id'=>$order_model->order_id])->all();
        }
        if($order_products){
            $sub_query=ReturnBase::find()->select('return_id')->where(['order_id'=>$order_model->order_id,'return_status_id'=>['1','2','3','4','5']]);
            foreach ($order_products as $op){

                $sub_commission=ReturnProduct::find()->where(['return_id'=>$sub_query,'from_table'=>'order_product','from_id'=>$op->order_product_id])->sum('commission');
                if($sub_commission){
                    $commission=$sub_commission?max(0,$op->commission-$sub_commission):0;
                }else{
                    $commission+=$op->commission;
                }
            }
        }
        return $commission;
    }

    public function actionCommission()
    { //来自客户分享的提佣
        $order_id = '151740';
        if ($order_model = Order::findOne(['order_id' => $order_id])) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if($order_model->current_source_customer_id){
                    $data_left = $this->getCurrentCommission($order_model);
                    if($data_left['current_commission'] > 0){
                        if($data_left['left_order_products_array']){
                            $data_mark['type'] = 'part';
                        }else{
                            $data_mark['type'] = 'all';
                        }
                        $data_mark['commission_order_products'] = $data_left['in_order_products_array']; //该次提成的提成商品 记录到mark中方便查找追踪

                        $this->setCustomerCommission($order_model->current_source_customer_id,$data_left['current_commission'],$order_model->order_id,json_encode($data_mark),'aff_personal');
                    }
                }

                if ($order_model->source_customer_id) {
                    $commissioin = 0;
                    $data_mark = [];
                    if(isset($data_left)){
                        $left_count = count($data_left['left_order_products_array']);
                        if( $left_count> 0){
                            if($left_count < count($order_model->orderProducts) ){
                                $data_mark['type'] = 'part';
                                $data_mark['commission_order_products'] = array_column($data_left['left_order_products_array'],'product_id');
                                $commissioin = $this->getCommision($order_model,$data_left['left_order_products_array']);
                            }
                        }
                    }else{
                        $data_mark['type'] = 'all';
                        $commissioin = $this->getCommision($order_model);
                    }
                    if($commissioin > 0){
                        $this->setCustomerCommission($order_model->source_customer_id,$commissioin,$order_model->order_id,json_encode($data_mark),'aff_customer');
                    }
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
    }
    private function setCustomerCommission($source_customer_id,$amout,$order_id,$remark='',$aff_type='aff_customer'){
        if (!$model = CustomerCommission::findOne(['customer_id' => $source_customer_id])) {
            $model = new CustomerCommission();
            $model->customer_id = $source_customer_id;
        }
        $model->amount = $model->amount + floatval($amout);
        if (!$model->save()) {
            throw new \Exception(json_encode($model->errors));
        }
        if(!$flow_model = CustomerCommissionFlow::findOne(['type_id'=>'order_id','customer_id'=>$model->customer_id,'aff_type'=>$aff_type])){
            $flow_model = new CustomerCommissionFlow();
            $flow_model->is_notice = 0;
        }
        $flow_model->type="order";
        $flow_model->type_id=$order_id;
        $flow_model->customer_id = $model->customer_id;
        $flow_model->title = "入帐";
        $flow_model->amount = floatval($amout);
        $flow_model->balance = $model->amount;
        $flow_model->remark = "订单收益";
        $flow_model->data_mark = $remark;
        $flow_model->status = 1;
        $flow_model->create_at = time();
        $flow_model->aff_type = $aff_type;
        if(!$flow_model->save()){
            throw new \Exception(json_encode($flow_model->errors));
        }
//        if($flow_model->is_notice == 0){
//            $template_id = 'i1q1M2mGcTEeFJySynB8FODHTNpwQ7QTGR3zU8SOuHk';//佣金提醒
//            $url = 'https://m.mrhuigou.com/user-share/index';
//            if ( $user = User::findIdentity($flow_model->customer_id)) {
//                if ($open_id = $user->getWxOpenId()) {
//                    $msg = $this->getMessage("您获得了一笔新的佣金，点击消息查看收益情况", $flow_model);
//                    $body = [
//                        'touser' => $open_id,
//                        'template_id' => $template_id,
//                        'url' => $url,
//                        'topcolor' => '#173177',
//                        'data' => $msg
//                    ];
//                    $result = $this->send($body);
//                    if ($result['errcode'] == 0) {
//                        $flow_model->is_notice = 1;
//                        $flow_model->save();
//                    }
//                }
//            }
//        }


    }

}
