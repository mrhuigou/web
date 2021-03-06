<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/4
 * Time: 15:52
 */
namespace fx\controllers;
use api\models\V1\City;
use api\models\V1\District;
use api\models\V1\Zone;
use common\component\Curl\Curl;
use common\component\Helper\Map;
use fx\models\AffiliateOrderForm;
use api\models\V1\Affiliate;
use api\models\V1\AffiliatePlan;
use api\models\V1\AffiliatePlanDetail;
use api\models\V1\AffiliatePlanType;
use api\models\V1\Customer;
use api\models\V1\GroundPushPlan;
use api\models\V1\GroundPushPlanView;
use api\models\V1\GroundPushPoint;
use api\models\V1\GroundPushPointToCustomer;
use api\models\V1\GroundPushStock;
use api\models\V1\Order;
use api\models\V1\OrderHistory;
use api\models\V1\OrderMerge;
use api\models\V1\OrderProduct;
use api\models\V1\OrderShipping;
use api\models\V1\OrderTotal;
use api\models\V1\Product;
use common\component\Helper\OrderSn;
use common\component\Helper\Xcrypt;
use dosamigos\qrcode\QrCode;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class AffiliatePlanController extends \yii\web\Controller {
    private $key = 'C7CAED85F4C0B90876BF891FA5220C00';//加密密钥
    private $iv = '15A705E0DAD32735569AD606D93CABC7';
    public $layout = 'main_other';
	public function actionIndex()
	{

        $plan_code = \Yii::$app->request->get('plan_code');
        $type_code = \Yii::$app->request->get('type_code');

        //获取分销方案code编码
        if(!$plan_code){
            if ($model = AffiliatePlanType::findOne(['code' => $type_code, 'status' => 1])) {
                $plans = AffiliatePlan::find()->where(['type'=>$model->code,'status'=>1])->andWhere(['and','date_start < NOW()','date_end > NOW()'])->orderBy('date_start asc,date_end desc,affiliate_plan_id desc')->all();
                if(empty($plans)){
                    throw new NotFoundHttpException("没有找到相关分销方案");
                }
                $plan_code = $plans[0]->code;
            }else{
                throw new NotFoundHttpException("没有找到相关分销方案类型");
            }
        }

//        if (\Yii::$app->user->isGuest) {
//            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
//        }

        $cart = [];
        if(\Yii::$app->session->get("confirm_push")){
            $cart = json_decode(\Yii::$app->session->get("confirm_push"),true);
        }

        //进入页面自动绑定分销商信息分销商ID
        $affiliate_id = \Yii::$app->session->get('from_affiliate_uid');
        //获取当前分销商的分销商信息
        $affiliate_info = Affiliate::find()->where(['status'=>1,'affiliate_id'=>$affiliate_id])->one();

        if(!$affiliate_info){
            throw new NotFoundHttpException("分销商不存在!");
        }

        //正在进行的方案
        $affiliate_plan = AffiliatePlan::find()->where(['status' => 1,'code'=>$plan_code])->andWhere(['<', 'date_start', date('Y-m-d H:i:s')])->andWhere(['>', 'date_end', date('Y-m-d H:i:s')])->one();
        $products = [];
        $plan_countdown = 0;
        if ($affiliate_plan) {
            $plan_countdown = strtotime($affiliate_plan->date_end) - time();
            $affiliate_plans = AffiliatePlan::find()->where(['type'=>$affiliate_plan->type,'status'=>1])->andWhere(['and','date_start < NOW()','date_end > NOW()'])->orderBy('date_start asc,date_end desc,affiliate_plan_id desc')->all();

            if ($model = AffiliatePlanType::findOne(['code' => $affiliate_plan->type, 'status' => 1])) {
            }else{
                throw new NotFoundHttpException("没有找到相关分销方案类型");
            }
            $products = AffiliatePlanDetail::find()->where(['status' => 1, 'affiliate_plan_id' => $affiliate_plan->affiliate_plan_id])->orderBy('priority asc')->all();

        } else {
            throw new NotFoundHttpException("没有找到相关分销方案");
        }

        //计算总金额
        $total = 0;
        if($ground_push_base = \Yii::$app->session->get('ground_push_base')){
            $ground_push_base = json_decode($ground_push_base,true);
            foreach ($ground_push_base as $key => $value){
                if($key == $affiliate_plan->affiliate_plan_id){
                    continue;
                }
                $total = $total + $value['total'];
            }
        }

        //排除当前方案，其他方案的购物车
        $is_other_cart = false;
        if(!empty($cart)){
            foreach ($cart as $key => $value){
                if($is_other_cart == false && $key != $affiliate_plan->affiliate_plan_id){
                    $is_other_cart = true;
                }
            }
        }

        return $this->render('index', ['affiliate_plan' => $affiliate_plan,'plan_countdown' =>$plan_countdown,'products' => $products,'cart'=>$cart,'affiliate_info' => $affiliate_info,'affiliate_plans'=>$affiliate_plans,'total'=> $total,'is_other_cart'=> $is_other_cart]);

	}

	//分销方案  列表
    public function actionPlanInfo(){
        $status = 1;
        $message = "";
        $data = [];
        $cart = [];
        try {
            if(\Yii::$app->session->get("confirm_push")){
                $cart = \Yii::$app->session->get("confirm_push");
            }
            if ($type_code = \Yii::$app->request->get('type_code')) {
                /*获取滚动方案*/
                $plan_type = AffiliatePlanType::findOne(['code' => $type_code, 'status' => 1]);
                $plans = AffiliatePlan::find()->where(['affiliate_plan_type_id'=>$plan_type->id,'status'=>1])->andWhere(['and','date_start < NOW()','date_end > NOW()'])->all();

            } else {
                throw new BadRequestHttpException('错误请求', '1001');
            }
            if($wx_xcx = Yii::$app->request->get('wx_xcx',0)){

            }else{
                Yii::$app->session->remove('source_from_agent_wx_xcx');
                if ($plans) {
                    foreach ($plans as $key => $plan) {
                        //$data 的键值必须0，1,2，3,4如此递增

                        $products_old = AffiliatePlanDetail::find()->where(['status' => 1, 'affiliate_plan_id' => $plan->affiliate_plan_id])->orderBy('priority asc')->all();
                        $products = AffiliatePlanDetail::find()->where(['status' => 1, 'affiliate_plan_id' => $plan->affiliate_plan_id])->orderBy('priority asc')->asArray()->all();
                        if($products_old){
                            foreach ($products_old as $key1 => $value){
                                $products[$key1]['product_name'] = $value->product->description->name;
                                $products[$key1]['product_sku'] = $value->product->getSku();
                                $products[$key1]['image_url'] = \common\component\image\Image::resize($value->image_url,100,100);

                                if(empty($cart)){
                                    $quantity = 1;
                                }else{
                                    if(isset($cart[$value->product_code]) && $cart[$value->product_code] >0){ //购物车内有该商品
                                        $quantity = $cart[$value->product_code];
                                    }else{
                                        $quantity = 1;
                                    }
                                }
                                $products[$key1]['product_price'] =  round(bcmul($value->price,$quantity,4),2);
                                $products[$key1]['quantity'] =  $quantity;
                            }
                        }

                        $data[$key] = [
                            'name' => $plan->name,
                            'date_start' => $plan->date_start,
                            'date_end' => $plan->date_end,
                            'ship_end' => $plan->ship_end,
                            'affiliate_plan_id' => $plan->affiliate_plan_id,
                            'affiliate_plan_code' => $plan->code,
                            'products' => $products,
                        ];

                    }
                }
            }


        } catch (\Exception $e) {
            $status = 0;
            $message = $e->getMessage();
        }

        $data = ['status' => $status, 'data' => $data, 'message' => $message, "cart" => $cart];
        if (Yii::$app->request->get('callback')) {
            Yii::$app->getResponse()->format = "jsonp";
            return [
                'data' => $data,
                'callback' => \Yii::$app->request->get('callback')
            ];
        } else {
            Yii::$app->getResponse()->format = "json";
            return ['data' => $data];
        }
    }


	//地推人员 登录列表
	public function actionList(){
        $point_lists = [];
        $ground_push_plans = GroundPushPlan::find()->select(['*','CONCAT(DATE_FORMAT(end_date_time,"%Y-%m-%d "),shipping_end_time) AS shipping'])->where(['status'=>1])->andWhere(['and','begin_date_time < NOW()','type<>"CROSS"'])->having("shipping > NOW()")->all();
        if($ground_push_plans){
            foreach ($ground_push_plans as $ground_push_plan){
                if($ground_push_plan->point && $ground_push_plan->point->status == 1 && $ground_push_plan->point->type == 'SAME'){
                    $point_lists[] = $ground_push_plan->point;
                }
            }
        }
        //print_r($point_list);
        return $this->render('list',['point_lists'=>$point_lists]);
    }
    //地推人员 登录
    public function actionAjaxPass(){
        $point_id = \Yii::$app->request->post('point_id');
        $pass = \Yii::$app->request->post('pass');
        $result['status'] = false;
        if($ground_push_point = GroundPushPoint::findOne(['id'=>$point_id])){
            if($pass == $ground_push_point->pass){
                $time = time();
                $token = md5($ground_push_point->pass.'-'.$time);
                $result['status'] = true;
                $result['redirect'] = Url::to(['/app-scan/index','token'=>$token,'id'=>$ground_push_point->id,'t'=>$time]);
                return $this->redirect($result['redirect']);
            }else{
                $result['status'] = false;
                $result['msg'] = '口令错误';
            }
        }else{
            $result['msg'] = '数据错误';
        }
        return json_encode($result);
    }
    public function actionAjaxIsNewCustomer(){
        if (\Yii::$app->user->isGuest) {
            return null;
        }
        $customer_id = \Yii::$app->user->getId();
        if ($user_order = Order::find()->where(['customer_id' => $customer_id])->andWhere(["or", "order_status_id=2", "sent_to_erp='Y'"])->andWhere(["and","order_type_code <> 'GroundPush'"])->count("order_id")) {
            $status = false; //老用户
        }else{
            $status = true;
        }
        return json_encode(['status'=>$status]);
    }
    public function actionAjaxOrderStatus(){
        $order_no = \Yii::$app->request->get("order_no");
        $reslut['status'] = false;
        if($order = Order::findOne(['order_no'=>$order_no])){
            if($order->order_status_id == 10){
                $reslut['status'] = true;
            }
        }
        return json_encode($reslut);
    }

	public function actionUpdateItem(){
		try {
			if (\Yii::$app->request->getIsPost() && \Yii::$app->request->isAjax) {
				$code=\Yii::$app->request->post('item');
				$qty=\Yii::$app->request->post('qty');
				$push_id=\Yii::$app->request->post('id');
				if($model=AffiliatePlanDetail::findOne(['product_code'=>$code,'affiliate_plan_detail_id'=>$push_id])){
                    $info = AffiliatePlan::find()->where(['status' => 1,'affiliate_plan_id'=>$model->affiliate_plan_id])->andWhere(['<', 'date_start', date('Y-m-d H:i:s')])->andWhere(['>', 'date_end', date('Y-m-d H:i:s')])->one();
                    if($info){
                        if($product=AffiliatePlanDetail::findOne(['affiliate_plan_detail_id'=>$push_id,'product_code'=>$code,'status'=>1])){
                            if($product->max_buy_qty && $product->max_buy_qty<$qty ){
                                throw new ErrorException('此商品最大限购'.$product->max_buy_qty.'个');
                            }else{
                                $data = ['status' => 1, 'sub_total' =>($product->price_type == 1 ? $product->price:$product->product->productBase->price)*$qty,'qty'=>$qty];
                            }
                        }else{
                            throw new ErrorException('此商品已经下架');
                        }
                    }else{
                        throw new ErrorException('分销方案已失效');
                    }
				}else{
					throw new ErrorException('库存异常');
				}
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
    //团长分销 切换分销方案 选中的商品自动 加入购物车
    public function actionSwitchPlanSubmit(){
//        \Yii::$app->session->remove("confirm_push");
//        \Yii::$app->session->remove("ground_push_base");
//        if (\Yii::$app->user->isGuest) {
//            return $this->redirect(['/site/login', 'redirect' => '/ground-push/index']);
//        }

        $affiliate_plan_id = \Yii::$app->request->post('affiliate_plan_id');

        $data_string = \Yii::$app->request->post("data");
        trim($data_string,';');
        $data_array = explode(';',$data_string);
        $cart = [];
        if(\Yii::$app->session->get('confirm_push')){
            $cart = json_decode(\Yii::$app->session->get('confirm_push'),true);
            if(array_key_exists($affiliate_plan_id,$cart)){
                unset($cart[$affiliate_plan_id]);
            }
            if($ground_push_base = \Yii::$app->session->get('ground_push_base')){
                $ground_push_base = json_decode($ground_push_base,true);
                if(array_key_exists($affiliate_plan_id,$ground_push_base)){
                    unset($ground_push_base[$affiliate_plan_id]);
                }
                \Yii::$app->session->set('ground_push_base',json_encode($ground_push_base));
            }
        }
        if ($data_array) {
            foreach ($data_array as $key => $value) {
                if ($value) {
                    $items = explode(',', $value);
                    list($product_code, $qty) = $items;
                    $cart[$affiliate_plan_id][$product_code] = $qty;
                }
            }
        }

        \Yii::$app->session->set('confirm_push', json_encode($cart));
        try {

            $plan_info = AffiliatePlan::find()->where(['affiliate_plan_id' => $affiliate_plan_id, 'status' => 1])->one();

            if ($model = AffiliatePlanType::findOne(['code' => $plan_info->type, 'status' => 1])) {
            }else{
                throw new Exception("没有找到相关分销方案类型");
            }

            if (strtotime($plan_info->date_start) > strtotime(date('Y-m-d H:i:s'))) {
                throw new Exception("分销活动未开始");
            }
            if (strtotime($plan_info->date_end) < strtotime(date('Y-m-d H:i:s'))) {
                throw  new  Exception("分销活动已结束");
            }

            $time = time();
            $end_time = strtotime(date("Y-m-d").' '. $plan_info->date_end);

            if( time() > strtotime( $plan_info->ship_end) ){
                throw  new  Exception("超过配送时间，请不要下单");
            }

            //检查库存（无库存处理）
            if ($cart && array_key_exists($affiliate_plan_id,$cart)) {
                $total = 0;
                foreach ($cart[$affiliate_plan_id] as $code => $qty) {
                    $affiliate_plan_detail = AffiliatePlanDetail::find()->where(['affiliate_plan_id' => $affiliate_plan_id, 'status' => 1, 'product_code' => $code])->one();
                    if ($affiliate_plan_detail->max_buy_qty && $affiliate_plan_detail->max_buy_qty < $qty) {
                        //购买数量超过最大购买数量
                        throw new Exception("最多购买".$affiliate_plan_detail->max_buy_qty.'件');
                    }
                    $price = $affiliate_plan_detail->price;
                    $total = round(bcadd($total, bcmul($affiliate_plan_detail->price_type == 1 ? $affiliate_plan_detail->price:$affiliate_plan_detail->product->productBase->price, $qty,4),4),2);

                    //$this->submit();
                }

                $base = [];
//                if($base = \Yii::$app->redis->get('ground_push_base')){
//
//                }
                $base[$affiliate_plan_id]['total'] = $total;
                $base[$affiliate_plan_id]['platform_id'] = 1;
                $base[$affiliate_plan_id]['store_id'] = 1;
                $base[$affiliate_plan_id]['name'] = '青岛每日惠购';
                $base[$affiliate_plan_id]['url'] = 'https://m.mrhuigou.com/affiliate-plan/index';
                $base[$affiliate_plan_id]['affiliate_plan_id'] = $affiliate_plan_id;

                \Yii::$app->session->set('ground_push_base',json_encode($base));

                //$this->submit($base, $cart, $ground_push_plan_id);
            }


        }catch (Exception $e){
            $e->getMessage();
            $json['status']= false;
            $json['message'] = $e->getMessage();
            return json_encode($json);
        }

    }



	//地推活动购物车提交页面
	public function actionSubmit(){
//	    \Yii::$app->session->remove("confirm_push");
	    \Yii::$app->session->remove("ground_push_base");
//        if (\Yii::$app->user->isGuest) {
//            return $this->redirect(['/site/login', 'redirect' => '/ground-push/index']);
//        }

        $affiliate_plan_id = \Yii::$app->request->post('affiliate_plan_id');
	    $data_string = \Yii::$app->request->post("data");

	    trim($data_string,';');
	    $data_array = explode(';',$data_string);

        $cart = [];
        if($cart = \Yii::$app->session->get('confirm_push')){
            $cart = json_decode($cart,true);
            if(array_key_exists($affiliate_plan_id,$cart)){
                unset($cart[$affiliate_plan_id]);
            }
        }
        if ($data_array) {
            foreach ($data_array as $key => $value) {
                if ($value) {
                    $items = explode(',', $value);
                    list($product_code, $qty) = $items;
                    $cart[$affiliate_plan_id][$product_code] = $qty;
                }
            }
        }

        \Yii::$app->session->set('confirm_push', json_encode($cart));
        try {

            $affiliate_id = \Yii::$app->session->get('from_affiliate_uid');
            $affiliate_info = Affiliate::find()->where(['status'=>1,'affiliate_id'=>$affiliate_id])->one();

            if($cart){
                $totals = 0;
                foreach ($cart as $affiliate_plan_id => $products){
//                     $plan_info = AffiliatePlan::find()->where(['affiliate_plan_id' => $affiliate_plan_id, 'status' => 1])->one();

                    if($plan_info = AffiliatePlan::find()->where(['affiliate_plan_id' => $affiliate_plan_id, 'status' => 1])->one()){
                        if ($model = AffiliatePlanType::findOne(['code' => $plan_info->type, 'status' => 1])) {
                        }else{
                            throw new Exception("没有找到相关分销方案类型");
                        }

                        if (strtotime($plan_info->date_start) > strtotime(date('Y-m-d H:i:s'))) {
                            throw new Exception("分销活动未开始");
                        }
                        if (strtotime($plan_info->date_end) < strtotime(date('Y-m-d H:i:s'))) {
                            throw  new  Exception("分销活动已结束");
                        }

                        if( time() > strtotime( $plan_info->ship_end) ){
                            throw  new  Exception("超过配送时间，请不要下单");
                        }

                        if($products){
                            $total = 0;
                            foreach ($products as $code => $qty) {
                                $affiliate_plan_detail = AffiliatePlanDetail::find()->where(['affiliate_plan_id' => $affiliate_plan_id, 'status' => 1, 'product_code' => $code])->one();
//                            if ($affiliate_plan_detail->max_buy_qty && $affiliate_plan_detail->max_buy_qty < $qty) {
//                                //购买数量超过最大购买数量
//                                throw new Exception("最多购买".$affiliate_plan_detail->max_buy_qty.'件');
//                            }
                                $price = $affiliate_plan_detail->price;
                                $total = round(bcadd($total, bcmul($affiliate_plan_detail->price_type == 1 ? $affiliate_plan_detail->price:$affiliate_plan_detail->product->productBase->price, $qty,4),4),2);

                                //$this->submit();
                            }

                            $base[$affiliate_plan_id]['total'] = $total;
                            $base[$affiliate_plan_id]['platform_id'] = 1;
                            $base[$affiliate_plan_id]['store_id'] = 1;
                            $base[$affiliate_plan_id]['name'] = '青岛每日惠购';
                            $base[$affiliate_plan_id]['url'] = 'https://m.mrhuigou.com/affiliate-plan/index';
                            $base[$affiliate_plan_id]['affiliate_plan_id'] = $affiliate_plan_id;
                            $base[$affiliate_plan_id]['affiliate_plan_code'] = $plan_info->code;
                            $base[$affiliate_plan_id]['affiliate_code'] = $affiliate_info->code;

                            \Yii::$app->session->set('ground_push_base',json_encode($base));

                        }

                        $totals = round(bcadd($totals, $total,4),2);
                    }

                }

                return $this->redirect(['/affiliate-plan/confirm','plan_id'=>$affiliate_plan_id]);
            }


        }catch (Exception $e){
            $e->getMessage();
            $json['status']= false;
            $json['message'] = $e->getMessage();
            return json_encode($json);
        }

    }
    //提货确认页面
    public function actionConfirm(){

	    $cart = [];
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => '/affiliate-plan/confirm']);
        }

	    if(\Yii::$app->session->get("confirm_push")){
            $cart = json_decode(\Yii::$app->session->get("confirm_push"),true);
        }else{
	        return $this->redirect('/order/index');
        }


        if($user = Customer::findOne(['customer_id'=>\Yii::$app->user->getId()])){
	        if(!$user->telephone || !$user->telephone_validate){
                return $this->redirect(['/user/security-set-telephone', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
            }
        }

        if(\Yii::$app->session->get("ground_push_base")){
	        $base = json_decode(\Yii::$app->session->get("ground_push_base"),true);
        }else{
             return $this->redirect('/order/index');
        }
//        if(\Yii::$app->request->get('plan_id')){
//            $plan_id = \Yii::$app->request->get('plan_id');
//        }else{
//            return $this->redirect('/order/index');
//        }
//
//        $plan = AffiliatePlan::findOne(['affiliate_plan_id'=>$plan_id]);

        $sub_totals = 0;
        $carts = [];
        if($cart){
            foreach ($cart as $plan_id => $products){
                $count = 0;
                $sub_total = 0;
                foreach ($products as $code => $qty){
                    $plan_view = AffiliatePlanDetail::findOne(['affiliate_plan_id'=>$plan_id,'product_code'=>$code,'status'=>1]);
                    $product_total = 0;
                    if($plan_view){
                        $product_total = bcmul($qty,$plan_view->price_type == 1 ? $plan_view->price:$plan_view->product->productBase->price,4);
                        $product_total = round($product_total,2);
                        $carts[$plan_id][$count]['pv'] = $plan_view;
                        $carts[$plan_id][$count]['qty'] = $qty;
                        $carts[$plan_id][$count]['product_total'] = $product_total;

                        $count++;
                    }
                    $sub_total = $sub_total + $product_total;
                }
                $sub_totals = $sub_total + $sub_totals;
            }
            $pay_total = $sub_totals;
            $totals = $this->getPushOrderTotals($sub_totals);

            $affiliate_id = \Yii::$app->session->get('from_affiliate_uid');
            $affiliate_info = Affiliate::find()->where(['status'=>1,'affiliate_id'=>$affiliate_id])->one();

            $model = new AffiliateOrderForm($affiliate_id);
            if(\Yii::$app->request->isPost){
                //$this->submit();
                $telephone = \Yii::$app->request->post("telephone");
                $firstname = \Yii::$app->request->post("firstname");
                $region = \Yii::$app->request->post("confirm_address");
                $address_1 = \Yii::$app->request->post("confirm_address_1");
                $shipping_method = \Yii::$app->request->post("shipping_method");
                if(!empty($region)){
                    $region = explode('-',$region);
                    $address['province'] = $region[0];
                    $address['city'] = $region[1];
                    $address['district'] = $region[2];
                }

                $address['telephone'] = $telephone;
                $address['firstname'] = $firstname;
//                $address['region'] = $region;
                $address['address_1'] = $address_1;
                $address['shipping_method'] = $shipping_method;
                $address['confirm_lng'] = \Yii::$app->request->post("confirm_lng");
                $address['confirm_lat'] = \Yii::$app->request->post("confirm_lat");
                $trade_no = $this->submit($base,$cart,$address);

                \Yii::$app->session->remove("confirm_push");
                \Yii::$app->session->remove("ground_push_base");

                return $this->redirect(['payment/index', 'trade_no' => $trade_no, 'showwxpaytitle' => 1]);
//                return $this->redirect('https://m.mrhuigou.com/payment/index?trade_no='.$trade_no.'&showwxpaytitle=1');
            }
            $shipping_method = \Yii::$app->session->get("shipping_method")?:1;
            if($shipping_address = \Yii::$app->session->get("shipping_address")){
                $shipping_address = json_decode($shipping_address,true);
            }else{
                //获取当前用户的最后一次购物的收货地址
                $last_order_info = Order::find()->where(['customer_id'=> \Yii::$app->user->getId(),'sent_to_erp'=> 'Y'])->orderBy('date_added desc')->one();
                $shipping_address = [];
                if($last_order_info){
                    $shipping_address['zone_name'] = $last_order_info->orderShipping->shipping_zone;
                    $shipping_address['city_name'] = $last_order_info->orderShipping->shipping_city;
                    $shipping_address['district_name'] = $last_order_info->orderShipping->shipping_district;
                    $shipping_address['address'] = $last_order_info->orderShipping->shipping_address_1;
                    $shipping_address['lng'] = $last_order_info->orderShipping->lng;
                    $shipping_address['lat'] = $last_order_info->orderShipping->lat;
                }
                \Yii::$app->session->set("shipping_address",json_encode($shipping_address));
            }
            return $this->render('confirm', ['carts'=>$carts,'totals'=>$totals,'pay_total'=>$pay_total ,'affiliate_info'=>$affiliate_info,'model' => $model,'shipping_method'=> $shipping_method,'shipping_address'=> $shipping_address]);
        }else{
            return $this->redirect('/order/index');
        }



    }
    //根据用户选择的配送方式获取收货地址
    public function actionDistributionAddress(){

        $distribution_type = \Yii::$app->request->post("distribution_type");
//        $distribution_type = \Yii::$app->request->get("distribution_type");
        try {

            if($distribution_type){
                $address = [];
                $fx_user_info = json_decode(\Yii::$app->redis->get("fx_user_info"),true);
                if($distribution_type == 1){
                    //获取最后下单的地址
                    $last_order_info = Order::find()->where(['customer_id'=> \Yii::$app->user->getId(),'sent_to_erp'=> 'Y'])->orderBy('date_added desc')->one();
                    if($last_order_info){
                        $address['zone'] = $last_order_info->orderShipping->shipping_zone;
                        $address['city'] = $last_order_info->orderShipping->shipping_city;
                        $address['district'] = $last_order_info->orderShipping->shipping_district;
                        $address['address_1'] = $last_order_info->orderShipping->shipping_address_1;
                        $address['address_username'] = $last_order_info->orderShipping->shipping_firstname;
                        $address['address_telephone'] = $last_order_info->orderShipping->shipping_telephone;
                    }
                }
                if($distribution_type == 2){
                    $affiliate_id = \Yii::$app->session->get('from_affiliate_uid')?:2; //默认团长
                    $affiliate_info = Affiliate::find()->where(['status'=>1,'affiliate_id'=>$affiliate_id])->one();
                    $address['zone'] = $affiliate_info->zone_name;
                    $address['city'] = $affiliate_info->city_name;
                    $address['district'] = $affiliate_info->district_name;
                    $address['address_1'] = $affiliate_info->address;
                    $address['address_username'] = \Yii::$app->user->identity->firstname;
                    $address['address_telephone'] = \Yii::$app->user->identity->telephone;
                }
            }

            if(\Yii::$app->session->get("fx_address")){
                $address = json_decode(\Yii::$app->session->get("fx_address"),true);
                $address['address_username'] = $address['firstname'];
                $address['address_telephone'] = $address['telephone'];
                $address['zone'] = $address['province'];
            }
            $json['status'] = true;
            $json['data'] = ['address'=> $address,'distribution_type'=> $distribution_type];

        }catch (Exception $e){
            $e->getMessage();
            $json['status']= false;
            $json['message'] = $e->getMessage();
        }
        return json_encode($json);
    }

    //编辑收货地址
    public function actionEditAddress(){
	    $address = [];

        if($shipping_address = \Yii::$app->session->get("shipping_address")){
            $address = json_decode($shipping_address,true);
        }

        if (\Yii::$app->request->get('redirect')) {
            $redirect_url = \Yii::$app->request->get('redirect');
        } elseif (\Yii::$app->session->get('redirect_url')) {
            $redirect_url = \Yii::$app->session->get('redirect_url');
        } else {
            $redirect_url = Url::to(["/affiliate-plan/confirm"],true);
        }
        try {

            if($post = \Yii::$app->request->isPost){
                $region = \Yii::$app->request->post('region');
                $address_edit['address'] = \Yii::$app->request->post('address_1');
                $redirect_url = \Yii::$app->request->post('redirect_url');

                if(!empty($region)){
                    $region = explode(' ',$region);
                    $address_edit['zone_name'] = $region[0];
                    $address_edit['city_name'] = $region[1];
                    $address_edit['district_name'] = $region[2];
                }
//                收货地址判断 （经纬度计算）
                $curl=new Curl();
                $url='http://apis.map.qq.com/ws/geocoder/v1/';
                $result=$curl->get($url,['address'=>$address_edit['city_name'].$address_edit['district_name'].$address_edit['address'],'key'=>'GNWBZ-7FSAR-JL5WY-WIDVS-FHLY2-JVBEC']);

                if($result && $result->status==0 && $result->result){
                    $address_edit['lat']=$result->result->location->lat;
                    $address_edit['lng']=$result->result->location->lng;
                    if($result->result->address_components->province){
                        $address_edit['province']=trim($result->result->address_components->province);
                    }
                    if($result->result->address_components->city){
                        $address_edit['city']=trim($result->result->address_components->city);
                    }
                    if($result->result->address_components->district){
                        $address_edit['district']=trim($result->result->address_components->district);
                    }
                }else{
                    throw  new Exception('您输入的地址不在配送范围之内!');
                }

                if($address_edit['lat'] && $address_edit['lng']){
                    $center_lat=36.1516;
                    $center_lng=120.39822;

                    if(($distance=Map::GetShortDistance($center_lng,$center_lat,$address_edit['lng'],$address_edit['lat']))>15*1000){
                        throw  new Exception('您输入的地址不在配送范围之内!');
                    }
                }

                if($address_edit['province'] && !in_array($address_edit['province'],['山东省'])){
                    throw  new Exception('超出配送范围，请重新选择！');
                }
                if($address_edit['city'] && !in_array($address_edit['city'],['青岛市'])){
                    throw  new Exception('超出配送范围，请重新选择！');
                }
                // $district_array=['市南区','市北区','四方区','李沧区','崂山区','黄岛区'];
                $active_districts = District::find()->select('name')->where(['is_use'=>1])->all();
                foreach ($active_districts as $active_district){
                    $district_array[] = $active_district->name;
                }
                if($address_edit['district'] && !in_array($address_edit['district'],$district_array)){
                    throw  new Exception('超出配送范围，请重新选择！');
                }

                \Yii::$app->session->set("shipping_address",json_encode($address_edit));
                return $this->redirect($redirect_url);
//                return $this->redirect(['/affiliate-plan/confirm']);
            }

            return $this->render('edit-address', ['address'=>$address,'redirect_url'=>$redirect_url]);

        }catch (Exception $e){
            $e->getMessage();
            $json['status']= false;
            $json['message'] = $e->getMessage();
            return json_encode($json);
        }

    }

    public function validate(){
	    return true;
    }
    //生成订单页面
    private function submit($bases,$carts,$address=[])
    {   //交易号
        $trade_no = "";

        //分销用户信息
        $fx_user_info = json_decode(\Yii::$app->redis->get("fx_user_info"),true);
        if ($this->validate()) {
            $merge_order_ids = [];
            $merge_total = 0;
            //$product_stock = [];
            $transaction = \Yii::$app->db->beginTransaction();
            try {

                //根据购物车里商品 按照不同的方案 生成不同的订单
                foreach ($carts as $plan_id => $cart){
                    $base = $bases[$plan_id];

                    $comment = "";
                    if($address['shipping_method'] == '1'){
                        $comment = "<配送到家>";
                    }else{
                        $comment = "<团长处自提>";
                    }
                    //订单主数据
                    $Order_model = new Order();
                    $Order_model->order_no = OrderSn::generateNumber();
                    $Order_model->order_type_code = 'Affiliate';
                    $Order_model->platform_id = $base['platform_id'];
                    $Order_model->platform_name = "每日惠购";
                    $Order_model->platform_url = \Yii::$app->request->getHostInfo();
                    $Order_model->store_id = $base['store_id'];
                    $Order_model->store_name = $base['name'];
                    $Order_model->store_url = $base['url'];
                    $Order_model->customer_group_id = \Yii::$app->user->identity['customer_group_id'];
                    $Order_model->customer_id = \Yii::$app->user->getId();
                    $Order_model->firstname = \Yii::$app->user->identity['firstname'] ? \Yii::$app->user->identity['firstname'] :\Yii::$app->request->post("firstname");;
                    $Order_model->lastname = \Yii::$app->user->identity['lastname'];
                    $Order_model->email = \Yii::$app->user->identity['email'];
                    $Order_model->telephone = \Yii::$app->user->identity['telephone'];
                    $Order_model->gender = \Yii::$app->user->identity['gender'];
                    $Order_model->payment_method = "";
                    $Order_model->payment_code = "";
                    $Order_model->total = $base['total'];
                    $Order_model->comment = $comment;//订单备注
                    $Order_model->order_status_id = 1;
                    $affiliate_id = \Yii::$app->session->get('from_affiliate_uid')?:0;

                    $Order_model->affiliate_id = $affiliate_id;
                    $Order_model->commission=$this->getOrderCommission($base['total'],$affiliate_id);
                    $Order_model->source_customer_id=0;
                    $Order_model->language_id = 2;
                    $Order_model->currency_id = 4;
                    $Order_model->currency_code = 'CNY';
                    $Order_model->currency_value = 1;
                    $Order_model->ip = \Yii::$app->request->userIP;
                    $Order_model->user_agent = \Yii::$app->request->userAgent;
                    $Order_model->accept_language = \Yii::$app->request->getPreferredLanguage();
                    $Order_model->date_added = date("Y-m-d H:i:s", time());
                    $Order_model->date_modified = date("Y-m-d H:i:s", time());
                    $Order_model->invoice_temp = '不需要发票';
                    $Order_model->invoice_title = "";

                    $Order_model->sent_to_erp = "N";
                    $Order_model->affiliate_code = $base['affiliate_code'];
                    $Order_model->affiliate_plan_code = $base['affiliate_plan_code'];
                    if (!$Order_model->save(false)) {
                        throw new \Exception("订单数据异常");
                    }


                    $point = AffiliatePlan::findOne($base['affiliate_plan_id']);
                    if($point){
                        $delivery_time = '不限';
                        $zone=Zone::findOne(['name'=>$address['province']]);
                        $city=City::findOne(['name'=>$address['city'],'zone_id'=>$zone?$zone->zone_id:0]);
                        $district=District::findOne(['name'=>$address['district'],'city_id'=>$city?$city->city_id:0]);
                        $shipping_address = [
                            'shipping_method' => '每日惠购配送',
                            'shipping_code' => 'limit',
                            'delivery_code' => 'limit',
                            'delivery_date' => date("Y-m-d",strtotime($point->ship_end)),
                            'delivery_time' => $delivery_time,
                            'delivery_station_id' => 0,
                            'delivery_station_code' => '',
                            'username' => $address['firstname'],
                            'telephone' => $address['telephone'],
                            'address' => $address['address_1'],
                            'postcode' => 266000,
                            'zone' => $address['province']? : "",
                            'zone_code' => $zone?$zone->code:"",
                            'zone_id' => $zone?$zone->zone_id:0,
                            'city' => $address['city']? : "",
                            'city_code' => $city?$city->code: "",
                            'city_id' => $city?$city->city_id:0,
                            'district' => $address['district']? : "",
                            'district_code' => $district?$district->code: "",
                            'district_id' => $district?$district->district_id:0,
                            'lat' => $address['confirm_lat'],
                            'lng' => $address['confirm_lng'],
                            'is_delivery' => 1,
                        ];

                        if ($shipping_address) {
                            $Order_Shipping = new OrderShipping();
                            $Order_Shipping->order_id = $Order_model->order_id;
                            $Order_Shipping->station_id = $shipping_address['delivery_station_id'];
                            $Order_Shipping->station_code = $shipping_address['delivery_station_code'];
                            $Order_Shipping->shipping_firstname = $shipping_address['username'];
                            $Order_Shipping->shipping_telephone = $shipping_address['telephone'];
                            $Order_Shipping->shipping_address_1 = $shipping_address['address'];
                            $Order_Shipping->shipping_postcode = $shipping_address['postcode'];
                            $Order_Shipping->shipping_zone = $shipping_address['zone'];
                            $Order_Shipping->shipping_zone_id = $shipping_address['zone_id'];
                            $Order_Shipping->shipping_zone_code = $shipping_address['zone_code'];
                            $Order_Shipping->shipping_city = $shipping_address['city'];
                            $Order_Shipping->shipping_city_id = $shipping_address['city_id'];
                            $Order_Shipping->shipping_city_code = $shipping_address['city_code'];
                            $Order_Shipping->shipping_district = $shipping_address['district'];
                            $Order_Shipping->shipping_district_id = $shipping_address['district_id'];
                            $Order_Shipping->shipping_district_code = $shipping_address['district_code'];
                            $Order_Shipping->shipping_method = $shipping_address['shipping_method'];
                            $Order_Shipping->shipping_code = $shipping_address['shipping_code'];
                            $Order_Shipping->delivery_code = $shipping_address['delivery_code'];
                            $Order_Shipping->delivery = $shipping_address['delivery_date'];
                            $Order_Shipping->delivery_time = $shipping_address['delivery_time'];
                            $Order_Shipping->lat = $shipping_address['lat'];
                            $Order_Shipping->lng = $shipping_address['lng'];
                            $Order_Shipping->is_delivery = $shipping_address['is_delivery'];
                            if (!$Order_Shipping->save(false)) {
                                throw new \Exception("订单收货地址异常");
                            }

                        }
                    }


                    //添加商品信息
                    if (isset($cart) ) {
                        $total_commission = 0;
                        $sum_product_total = 0;
                        foreach ($cart as $code => $qty) {
                            $product = Product::findOne(['product_code'=>$code]);
                            $product_total = 0;

                            //$product_price = $product->getPrice();
                            $affiliate_plan_detail = AffiliatePlanDetail::find()->where(['affiliate_plan_id'=>$base['affiliate_plan_id'],'status'=>1,'product_code'=>$code])->one();
                            if($affiliate_plan_detail->max_buy_qty < $qty){
                                //购买数量超过最大购买数量
                            }
                            $price = $affiliate_plan_detail->price_type == 1 ? $affiliate_plan_detail->price:$affiliate_plan_detail->product->productBase->price;
                            $product_total =  round(bcmul( $price, $qty,4),2);
                            $sum_product_total = bcadd($sum_product_total,$product_total,4);


                            $promotion_id = 0;
                            $promotion_detail_id = 0;

                            $Order_product = new OrderProduct();
                            $Order_product->order_id = $Order_model->order_id;
                            $Order_product->product_base_id = $product->product_base_id;
                            $Order_product->product_base_code = $product->product_base_code;
                            $Order_product->product_id = $product->product_id;
                            $Order_product->product_code = $product->product_code;
                            $Order_product->model = $product->model;
                            $Order_product->name = $product->description->name;
                            $Order_product->quantity = $qty;
                            $Order_product->price = $price;
                            $Order_product->total = $product_total;
                            $Order_product->reward = $product->points;
                            $Order_product->unit = $product->unit;
                            $Order_product->format = $product->format;
                            $Order_product->sku_name = $product->getSku();
                            $Order_product->pay_total = $product_total;
                            $Order_product->refund_qty = 0;
                            $Order_product->promotion_id = $promotion_id;
                            $Order_product->promotion_detail_id = $promotion_detail_id;
                            $Order_product->commission=$this->getProductCommission($Order_model->affiliate_id,$affiliate_plan_detail->affiliate_price,$qty);

                            if (!$Order_product->save(false)) {
                                throw new \Exception("商品创建失败");
                            }
                            $total_commission = bcadd($total_commission,$Order_product->commission,4);
                        }
                        $total_commission = round($total_commission,2);
                        //更新订单佣金
                        $Order_model = Order::findOne($Order_model->order_id);
                        $Order_model->commission=$total_commission;
                        if (!$Order_model->save(false)) {
                            throw new \Exception("订单数据异常");
                        }
                    }
                    $sum_product_total = round($sum_product_total,2);
                    $totals = $this->getPushOrderTotals($sum_product_total,$Order_model->order_id);
                    //添加订单总计信息
                    if ($totals) {
                        foreach ($totals as $total) {
                            $Order_total = new OrderTotal();
                            $Order_total->order_id = $Order_model->order_id;
                            $Order_total->code = $total['code'];
                            $Order_total->title = $total['title'];
                            $Order_total->text = '￥' . $total['value'];
                            $Order_total->value = $total['value'];
                            $Order_total->sort_order = $total['sort_order'];
                            $Order_total->code_id = isset($total['code_id']) ? $total['code_id'] : "";
                            $Order_total->customer_code_id = isset($total['customer_coupon_id']) ? $total['customer_coupon_id'] : "";
                            if (!$Order_total->save(false)) {
                                throw new \Exception("订单小计异常");
                            }
                        }
                    }

                    //添加订单历史记录
                    $Order_history = new OrderHistory();
                    $Order_history->order_id = $Order_model->order_id;
                    $Order_history->order_status_id = 1;
                    $Order_history->comment = '订单提交成功';
                    $Order_history->date_added = date('Y-m-d H:i:s', time());
                    if (!$Order_history->save(false)) {
                        throw new \Exception("订单记录异常");
                    }


                    //创建合并订单进行支付
                    $merge_order_ids[] = $Order_model->order_id;
                    $merge_total = round(bcadd($merge_total, $Order_model->total, 4),2);

                }

                $model = new OrderMerge();
                $model->merge_code = OrderSn::generateNumber();
                $model->order_ids = implode(',', $merge_order_ids);
                $model->customer_id = $fx_user_info['customer_id'];
                $model->total = $merge_total;
                $model->status = 0;
                $model->date_added = date("Y-m-d H:i:s");
                $model->date_modified = date("Y-m-d H:i:s");
                if (!$model->save(false)) {
                    throw new \Exception(json_encode($model->errors));
                }
                $trade_no = $model->merge_code;
                $transaction->commit();

            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new NotFoundHttpException($e->getMessage());
            }
        }
        return $trade_no;
    }

    private function getPushOrderTotals($sum_product_total,$order_id=0){

        $totals = [];
        if($order_id){
            $total['order_id'] = $order_id;
        }
        $total['code'] = 'sub_total';
        $total['title'] = '商品总额';
        $total['text'] = '¥'.$sum_product_total;
        $total['value'] = $sum_product_total;
        $total['sort_order'] = '1';
        $totals[] = $total;

        $total = [];
        if($order_id){
            $total['order_id'] = $order_id;
        }
        $total['code'] = 'shipping';
        $total['title'] = '固定运费';
        $total['text'] = '¥ 0';
        $total['value'] = 0;
        $total['sort_order'] = '2';
        $totals[] = $total;

        $total = [];
        if($order_id){
            $total['order_id'] = $order_id;
        }
        $total['code'] = 'total';
        $total['title'] = '订单总计';
        $total['text'] = '¥ '.$sum_product_total;
        $total['value'] = $sum_product_total;
        $total['sort_order'] = '3';
        $totals[] = $total;

        return $totals;

    }
    public function actionGetPointQrcode(){
        $level = 'L';             // 纠错级别
        $size = 5;                // 大小
        $point_id = \Yii::$app->request->get('ground_push_point_id');
        if($point = GroundPushPoint::findOne(['id'=>$point_id,'status'=>1])){
           if($point->leaflet){
               $url = Url::to(['/ground-push/index','leaflet'=>$point->leaflet],true);

               return QrCode::png($url,false , $level, $size);exit;
           }
        }
        //$key="C7CAED85F4C0B90876BF891FA5220C00";
    }
    public function actionGetQrcode(){
        $order_id = \Yii::$app->request->get('order_id');
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => '/ground-push/get-qrcode?order_id='.$order_id]);
        }

        try{
            if($order_id){
                $order = Order::findOne(['order_id'=>$order_id]);
                if($order && strtolower($order->order_type_code) == strtolower('groundpush')){
                    if($order->order_status_id == 2){
                        $data['status'] = true;
                        $data['img'] = $this->getQrcode($order_id,$order->customer_id);
                        return  json_encode($data);
                    }else{
                        throw  new  Exception("当前未支付或者已经收获了");
                    }
                }else{
                    throw new Exception("该订单非地推订单");
                }
            }else{
                throw new Exception("数据错误");
            }
        }catch (Exception $e){
            $data['status'] = false;
            $data['msg'] = $e->getMessage();
            return  json_encode($data);
        }



    }
    private function getQrcode($order_id,$customer_id = 0){

        $level = 'L';             // 纠错级别
        $size = 5;                // 大小

        //$key="C7CAED85F4C0B90876BF891FA5220C00";

        $str = 'order_id='.$order_id.'&customer_id='.$customer_id.'&time='.time();
        $encrypt = new  Xcrypt($this->key,'cbc',$this->iv);
        $encrypt_string = $encrypt->encrypt($str,'base64');
//echo  $encrypt_string;exit;
        QrCode::png($encrypt_string,false , $level, $size);exit;
    }
    public function actionGetOrderByString(){
        $type = \Yii::$app->request->post("type");//scan ,input  扫描二维码 ，input输入
        $string = \Yii::$app->request->post('code_string');
        $ground_push_code =  \Yii::$app->request->post('ground_push_code'); //地推点code
        if($type == 'scan'){
            $result = $this->validateScanString($string);
        }else{
            $result = $this->validateInputString($string);
        }
        if($result['status']){
            $order = $result['order'];
            $push_point_to_customer = GroundPushPointToCustomer::findOne(['order_id'=>$order->order_id]);
            if($ground_push_code == $push_point_to_customer->point->code){
                $result['html'] = $this->renderPartial('get-order-by-scan',['order'=>$result['order'],'scan_string'=>$string,'type'=>$type]);
            }else{
                $result['status'] = false;
                $result['message'] = "错误的提货点，该订单应该去<".$push_point_to_customer->point->name.'>去取货';
               // throw new Exception("错误的提货点，该订单应该去<".$push_point_to_customer->point->name.'>去取货');
            }
        }
        unset($result['order']);
        return json_encode($result);

    }
    //平台人员，确认该客户收到货
    public function actionConfirmSelfTake(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => '/ground-push/get-qrcode']);
        }
        $type = \Yii::$app->request->post("type");//scan ,input  扫描二维码 ，input输入
        $string = \Yii::$app->request->post('code_string');
        $ground_push_code = \Yii::$app->request->post('ground_push_code');
        if($type == 'scan'){
            $result = $this->validateScanString($string);
        }else{
            $result = $this->validateInputString($string);
        }

        if($result['status']){
            $order = $result['order'];
            $push_point_to_customer = GroundPushPointToCustomer::findOne(['order_id'=>$order->order_id]);
            if($ground_push_code != $push_point_to_customer->point->code){
                throw new Exception("错误的提货点，该订单应该去<".$push_point_to_customer->point->name.'>去取货');
            }
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $order = $result['order'];
                $order->order_status_id = 10;
                $order->save();

                $Order_history = new OrderHistory();
                $Order_history->order_id = $order->order_id;
                $Order_history->order_status_id = 10;
                $Order_history->comment = '客户自提成功';
                $Order_history->date_added = date('Y-m-d H:i:s', time());
                if (!$Order_history->save(false)) {
                    throw new \Exception("订单记录异常");
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $result['status'] = false;
                $result['message'] = '数据更新错误（成功前，该用户不能提走货物）====>'.$e->getMessage();
                $transaction->rollBack();
                throw new NotFoundHttpException($e->getMessage());
            }

        }
        return json_encode($result);
    }
    private function validateInputString($input_string){
        try{
            if($input_string){
                $xcrypt = new Xcrypt('qwertyu8','ecb');
                $de_code = $xcrypt->decrypt($input_string,'hex');
                    $order_id = $de_code;
                    $order = Order::findOne(['order_id'=>$order_id]);
                    if($order){
                        if($order->order_status_id == 2){
                            $result['order'] = $order;
                            $result['status'] = true;
                            $result['message'] = "提货成功";//$html;
                            return $result;

                        }else{
                            throw new Exception("未支付或者已经领取了");
                        }
                    }else{
                        throw new Exception("提货码输入不正确");
                    }
            }else{
                throw new Exception("非法请求");
            }
        }catch (Exception $e){
            $result['status'] = false;
            $result['message'] = $e->getMessage();
            return $result;
        }
    }

    private function validateScanString($scan_string){
        //$scan_string = "sH2xi3UK9+o8PrZyoXLV5JNPflZ9L7tfzhpV1OTnhNLKB0NeCDUI/b4CjlNj86qQC97S1DL7E6+SuiZMYTQclg==";
        try{
            if($scan_string){
                $Xencrypt = new  Xcrypt($this->key,'cbc',$this->iv);
                $decypt_string = $Xencrypt->decrypt($scan_string,'base64');

                $explode_arr = explode('&',$decypt_string);
                $params= [];
                if($explode_arr){
                    foreach ($explode_arr as  $key_value){
                        $key_value_arr = explode('=',$key_value);
                        $params[$key_value_arr[0]] = $key_value_arr[1];
                    }
                }

                if($params && count($params)==3){
                    $order_id = $params['order_id'];
                    $customer_id = $params['customer_id'];
                    $scan_create_time_unix = $params['time'];
                    $order = Order::findOne(['order_id'=>$order_id,'customer_id'=>$customer_id]);
                    if($order){
                        if($order->order_status_id == 2){

                            $result['order'] = $order;
                            $result['status'] = true;
                            $result['message'] = "提货成功";//$html;
                            return $result;

                        }else{
                            throw new Exception("未支付或者已经领取了");
                        }
                    }else{
                        throw new Exception("订单异常");
                    }

                }else{
                    throw new Exception("参数异常");
                }

            }else{
                throw new Exception("非法请求");
            }
        }catch (Exception $e){
            $result['status'] = false;
            $result['message'] = $e->getMessage();
            return $result;
        }
    }
    public function actionReceive(){
        //自动跳转
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => '/ground-push/receive']);
        }
        $point_to_customers = GroundPushPointToCustomer::find()->where(['customer_id'=>\Yii::$app->user->getId()])->all();
        if($point_to_customers){
            foreach ($point_to_customers as $point_to_customer){
                if($point_to_customer->order){
                    if($point_to_customer->order->order_status_id == 2){
                        $order_merage = OrderMerge::findOne(['order_ids'=>$point_to_customer->order->order_id]);
                        return $this->redirect(['/checkout/complate','trade_no'=>$order_merage->merge_code]);
                    }
                }
            }
        }

        return $this->redirect(['/share/index']);


    }

    protected function getOrderCommission($total,$affiliate_id){
        $commission=0;
        if($model=Affiliate::findOne(['affiliate_id'=>$affiliate_id,'status'=>1])){
            if($model->settle_type == 'order'){
                if($model->settle_commission == 'P'){
                    if($model->settle_commission == 'P'){
                        $commission = bcmul($model->commission,$total,2);
                    }else{
                        $commission = $model->commission;
                    }
                }else{
                    $commission = $model->commission;
                }
            }
        }
        return $commission;
    }

    protected function getProductCommission($affiliate_id,$affiliate_price,$qty=0){
        $commission=0;
        if($model=Affiliate::findOne(['affiliate_id'=>$affiliate_id,'status'=>1])){
            if($model->settle_type == 'product'){
                $commission = bcmul($qty,$affiliate_price,2);
            }
        }
        return $commission;
    }

}