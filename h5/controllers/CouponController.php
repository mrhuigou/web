<?php

namespace h5\controllers;
use api\models\V1\AdvertiseDetail;
use api\models\V1\Coupon;
use api\models\V1\CouponCate;
use api\models\V1\CouponHistory;
use api\models\V1\CouponRules;
use api\models\V1\CouponRulesDetail;
use api\models\V1\Order;
use api\models\V1\Product;
use api\models\V1\Store;
use common\component\image\Image;
use common\component\Track\Track;
use h5\models\ViewDeliveryForm;
use Yii;
use api\models\V1\CustomerCoupon;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class CouponController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>'/coupon/index']);
        }
        $data=[];
        $coupon_cate=CouponCate::find()->where(['status'=>1])->orderBy('sort_order asc')->all();
        if($coupon_cate){
           foreach($coupon_cate as $cate){
               $list=[];
               if($cate->coupon){
                   foreach($cate->coupon as $coupon){
                       if($coupon->coupon && $coupon->coupon->is_open && $coupon->coupon->status && strtotime($coupon->coupon->receive_begin_date)<=time() && strtotime($coupon->coupon->receive_end_date)>=time() ){
                           $list[]=$coupon->coupon;
                       }
                   }
               }else{
                   continue;
               }
               if($list){
	               krsort($list);
                   $data[]=[
                       'base'=>$cate,
                       'list'=>$list
                   ];
               }
           }
        }
        return $this->render('index',['model'=>$data]);
    }
    public function actionView(){

        if($id = Yii::$app->request->get("id")){
            $model = Coupon::findOne(['status'=>1,'coupon_id'=>$id]);
        }
        if($code =Yii::$app->request->get("code") ){
            $model = Coupon::findOne(['status'=>1,'code'=>$code]);
        }
        if($model){
            if(in_array($model->model,['ORDER','BUY_GIFTS'])){
                if(!empty($model->redirect_url)){
                    return $this->redirect($model->redirect_url);
                }
            }
            $coupon_product=[];
            if($model->product){
                foreach($model->product as $product){
                    if($product->status){
                        $coupon_product[]=$product;
                    }
                }
            }
            if($coupon_product){
                $customer_coupon=CustomerCoupon::findOne(['customer_id'=>Yii::$app->user->getId(),'coupon_id'=>$id]);
                return $this->render('view',['model'=>$model,'coupon_product'=>$coupon_product,'customer_coupon'=>$customer_coupon]);
            }else{
                return $this->redirect('/');
            }
        }else{
            throw new NotFoundHttpException('没有找到相关页面');
        }

    }

    /**
     * @desc 提货券详情
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionViewDelivery(){

//        if($id = Yii::$app->request->get("id")){//提货券id
//            $coupon = Coupon::findOne(['status'=>1,'coupon_id'=>$id]);
//        }
//        if($code =Yii::$app->request->get("code") ){
//            $coupon = Coupon::findOne(['status'=>1,'code'=>$code]);
//        }

        if($customer_coupon_id =Yii::$app->request->get("customer_coupon_id") ){//用户券id
            //用户券详细信息
            $customer_coupon = CustomerCoupon::findOne(['customer_coupon_id'=>$customer_coupon_id]);
        }

        if($customer_coupon && $customer_coupon->is_use == 0 && $customer_coupon->end_time >= date('Y-m-d')){
            $coupon = Coupon::findOne(['status'=>1,'coupon_id'=>$customer_coupon->coupon_id]);
        }else{
            throw new NotFoundHttpException('提货券已经使用或已经过期！');
        }
        if($coupon){
            if(in_array($coupon->model,['ORDER','BUY_GIFTS'])){
                if(!empty($coupon->redirect_url)){
                    return $this->redirect($coupon->redirect_url);
                }
            }
            $coupon_product=[];
            $product_id=[];
            if($coupon->product){
                $i = 0;
                foreach($coupon->product as $key => $product){
                    if($product->status){
                        $coupon_product[]=$product;
                        $product_id[$i]['product_id'] = $product->product_id;
                        $product_id[$i]['num'] = 1;
                        $i++;
                    }
                }
            }

            $key =  $store_id = 1;//店铺默认为家润每日惠购
            if ($product_id) {
                $products = [];
                $product_id = json_decode(json_encode($product_id));
                foreach ($product_id as $k => $value){
                    $products[] = Product::findOne($value->product_id);
                }
                $comfirm_orders[$key]['base'] = Store::findOne(['store_id' => $store_id]);
                $comfirm_orders[$key]['products'] = $products;
                $comfirm_orders[$key]['total'] = 0;
                $comfirm_orders[$key]['totals'] = [];
                $comfirm_orders[$key]['rate'] = [];
                $comfirm_orders[$key]['promotion'] = [];
                $comfirm_orders[$key]['coupon_gift'] = [];

                //计算商品金额
                $this->getSubTotal($comfirm_orders[$key]['totals'], $comfirm_orders[$key]['total'], $products);
                //计算运费金额
                $shipping_cost = 0;
                $comfirm_orders[$key]['totals'][] = $this->setTotalsData("固定运费",'shipping',$shipping_cost,2);
                $comfirm_orders[$key]['totals'][] = $this->setTotalsData($customer_coupon->coupon->name,'coupon','-'.$customer_coupon->coupon->discount,2,['customer_code_id'=> $customer_coupon->customer_coupon_id ,'code_id' => $customer_coupon->coupon_id]);
                $comfirm_orders[$key]['totals'][] = $this->setTotalsData("订单总计",'total',0,2);

            }


            if($coupon_product){
                $all_range = false;
                if(Yii::$app->request->get('range') == 'all_range'){
                    $all_range = true;
                }
                $model = new ViewDeliveryForm($comfirm_orders);


                if($all_range){
                    $model->in_range = 0;
                }else{
                    $model->in_range = 1;
                }
                $model->product_id = json_encode($product_id);
                $model->coupon_id = $coupon->coupon_id;
                $model->store_id = 1; //店铺id 默认为1 家润

                if ($model->load(Yii::$app->request->post()) && $order_no = $model->submit()) {

                    echo "<pre>";
                    var_dump(Yii::$app->request->post());die;
                    return $this->redirect('/');
                } else {
                    return $this->render('view-delivery',['model'=>$model,'coupon_product'=>$coupon_product]);

                }
             }else{
                return $this->redirect('/');
            }
        }else{
            throw new NotFoundHttpException('没有找到相关页面');
        }

    }

    public function actionAjaxCart(){
        $data =[];
        $max_buy_quantity = [];
        try{
            if (\Yii::$app->user->isGuest) {
                throw new ErrorException('登录后领取哦~');
            }
            if($data=Yii::$app->request->post('data')){

                    foreach($data as $value){
                        $max_buy_quantity = [];
                        if($model=Product::findOne(['product_id'=>$value['id']])){
                            $qty = $value['qty'];
                            if ($model) {
                                if ($stock_count = $model->getStockCount()) {
                                    if ($limit_max_qty = $model->getLimitMaxQty(\Yii::$app->user->getId())) {
                                        $stock_count = min($limit_max_qty, $stock_count);
                                    }
                                }
                            }
                            if ($stock_count > 0) {
                                if (\Yii::$app->cart->hasPosition($model->getCartPosition()->getId())) {
                                    $position = \Yii::$app->cart->getPositionById($model->getCartPosition()->getId());
                                   // \Yii::$app->cart->remove($model->getCartPosition());
                                    //$quantity = $qty + $position->getQuantity();
                                    if ($qty > 100 || $qty > $stock_count) {
                                        $max_buy_quantity['product_id'] = $model->product_id;
                                        $max_buy_quantity['max_quantity'] = min($stock_count, 100);
                                        throw new ErrorException('最大可购买数' . min($stock_count, 100) . '件');
                                    }
                                } else {
                                    if ($qty > 100 || $qty > $stock_count) {
                                        $max_buy_quantity['product_id'] = $model->product_id;
                                        $max_buy_quantity['max_quantity'] = min($stock_count, 100);
                                        throw new ErrorException('最大可购买' . min($stock_count, 100) . '件');
                                    }
                                }


                                if(\Yii::$app->cart->hasPosition($model->getCartPosition()->getId())){
                                    \Yii::$app->cart->update($model->getCartPosition(), $value['qty']);
                                }else{
                                    \Yii::$app->cart->put($model->getCartPosition(), $value['qty']);
                                }
                                //if(Yii::$app->request->get('is_push')){
                                //\Yii::$app->cart->put($model->getCartPosition(), $qty);
                                //}
                                $data = ['status' => 1, 'data' => \Yii::$app->cart->getCount()];
                            }else {
                                throw new ErrorException('库存不足');
                            }

                        }
                    }
	            $data= ['status' => 1, 'redirect' =>Url::to(['/cart/index'])];
            }else{
                throw new ErrorException('请选以下商品加入购物车！');
            }

        } catch (ErrorException $e) {

            $data = ['status' => 0, 'message' => $e->getMessage(),'max_buy_quantity'=>$max_buy_quantity];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionAjaxCartNew(){
        $data =[];
        $max_buy_quantity = [];
        try{
            if (\Yii::$app->user->isGuest) {
                throw new ErrorException('登录后领取哦~');
            }
            if($data=Yii::$app->request->post('data')){

                foreach($data as $value){
                    $max_buy_quantity = [];
                    if($model=Product::findOne(['product_id'=>$value['id']])){
                        $qty = $value['qty'];
                        if ($model) {
                            if ($stock_count = $model->getStockCount()) {
                                if ($limit_max_qty = $model->getLimitMaxQty(\Yii::$app->user->getId())) {
                                    $stock_count = min($limit_max_qty, $stock_count);
                                }
                            }
                        }
                        if ($stock_count > 0) {
                            if (\Yii::$app->cart->hasPosition($model->getCartPosition()->getId())) {
                                $position = \Yii::$app->cart->getPositionById($model->getCartPosition()->getId());
                                // \Yii::$app->cart->remove($model->getCartPosition());
                                //$quantity = $qty + $position->getQuantity();
                                if ($qty > 100 || $qty > $stock_count) {
                                    $max_buy_quantity['product_id'] = $model->product_id;
                                    $max_buy_quantity['max_quantity'] = min($stock_count, 100);
                                    throw new ErrorException('最大可购买数' . min($stock_count, 100) . '件');
                                }
                            } else {
                                if ($qty > 100 || $qty > $stock_count) {
                                    $max_buy_quantity['product_id'] = $model->product_id;
                                    $max_buy_quantity['max_quantity'] = min($stock_count, 100);
                                    throw new ErrorException('最大可购买' . min($stock_count, 100) . '件');
                                }
                            }


                            if(\Yii::$app->cart->hasPosition($model->getCartPosition()->getId())){
                                \Yii::$app->cart->update($model->getCartPosition(), $value['qty']);
                            }else{
                                \Yii::$app->cart->put($model->getCartPosition(), $value['qty']);
                            }
                            //if(Yii::$app->request->get('is_push')){
                            //\Yii::$app->cart->put($model->getCartPosition(), $qty);
                            //}
                            $data = ['status' => 1, 'data' => \Yii::$app->cart->getCount()];
                        }else {
                            throw new ErrorException('库存不足');
                        }

                    }
                }
                $data= ['status' => 1, 'redirect' =>Url::to(['/cart/index'])];
            }else{
                throw new ErrorException('请选以下商品加入购物车！');
            }

        } catch (ErrorException $e) {

            $data = ['status' => 0, 'message' => $e->getMessage(),'max_buy_quantity'=>$max_buy_quantity];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
    public function actionAjaxApply(){
        $data =[];
        try{
            if (\Yii::$app->user->isGuest) {
                throw new ErrorException('登录后领取！');
            }
            $coupon_id=Yii::$app->request->post('coupon_id');
            $coupon_code=Yii::$app->request->post('coupon_code');
			if($coupon_id){
				$model=Coupon::findOne(['coupon_id'=>$coupon_id,'is_open'=>1,'status'=>1]);
			}else{
				$model=Coupon::findOne(['code'=>$coupon_code,'is_open'=>1,'status'=>1]);
			}

            if($model){
                if(!$model->getUsedStatus(Yii::$app->user->getId())){
                    throw new ErrorException('您已经领取，使用后再领取！');
                }
                if(count($model->users)<$model->quantity){
                    if($model->user_limit){
                        $count=CustomerCoupon::find()->where(['customer_id'=>Yii::$app->user->getId(),'coupon_id'=>$model->coupon_id])->count();
                        if($count<$model->user_limit){
                            $customer_coupon=new CustomerCoupon();
                            $customer_coupon->customer_id=Yii::$app->user->getId();
                            $customer_coupon->coupon_id=$model->coupon_id;
                            $customer_coupon->is_use=0;
                            if ($model->date_type == 'DAYS') {
                                $customer_coupon->start_time = date('Y-m-d H:i:s', time());
                                $customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $model->expire_seconds);
                            } else {
                                $customer_coupon->start_time = $model->date_start;
                                $customer_coupon->end_time = $model->date_end;
                            }
                            $customer_coupon->date_added=date('Y-m-d H:i:s',time());
                            if(!$customer_coupon->save(false)){
                                throw new ErrorException("数据提交错误!");
                            }
                            $data= ['status' => 1, 'message' =>'领取成功！'];
                        }else{
                            throw new ErrorException("你已经领过了！!");
                        }
                    }
                }else{
                    throw new ErrorException("优惠券已被领光了!");
                }
            }else{
                throw new ErrorException("优惠券不存在或已过期");
            }
        } catch (ErrorException $e) {
            $data = ['status' => 0, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionApply(){
	    if (\Yii::$app->user->isGuest) {
		    return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
	    }
	    $coupon_code=Yii::$app->request->get('coupon_code');

	    $models=Coupon::find()->where(['code'=>explode(",",$coupon_code),'is_open'=>1,'status'=>1])->orderBy('date_end asc,discount desc')->all();
	    $res=[];
	    $pass=[];
	    if($models){
	    	foreach ($models as $model){
			    if(!$model->getUsedStatus(Yii::$app->user->getId())){
				    $pass[]=CustomerCoupon::findOne(['coupon_id'=>$model->coupon_id,'customer_id'=>Yii::$app->user->getId()]);
				    continue;
			    }
			    if(count($model->users)<$model->quantity){
				    if($model->user_limit){
					    $count=CustomerCoupon::find()->where(['customer_id'=>Yii::$app->user->getId(),'coupon_id'=>$model->coupon_id])->count();
					    if($count<$model->user_limit){
						    $customer_coupon=new CustomerCoupon();
						    $customer_coupon->customer_id=Yii::$app->user->getId();
						    $customer_coupon->coupon_id=$model->coupon_id;
						    $customer_coupon->is_use=0;
						    if ($model->date_type == 'DAYS') {
							    $customer_coupon->start_time = date('Y-m-d H:i:s', time());
							    $customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $model->expire_seconds);
						    } else {
							    $customer_coupon->start_time = $model->date_start;
							    $customer_coupon->end_time = $model->date_end;
						    }
						    $customer_coupon->date_added=date('Y-m-d H:i:s',time());
						    if(!$customer_coupon->save(false)){
							    throw new ErrorException("数据提交错误!");
						    }
						    $res[]=$customer_coupon;
					    }
				    }
			    }else{
				    $pass[]=CustomerCoupon::findOne(['coupon_id'=>$model->coupon_id]);
			    }
		    }
		    return $this->render('result',['res'=>$res,'pass'=>$pass]);
	    }else{
		    throw new NotFoundHttpException('没有找到相关页面');
	    }
    }


	public function actionComplateCoupon(){
		$data =[];
		try{
			if (\Yii::$app->user->isGuest) {
				throw new ErrorException('登录后领取！');
			}
			$coupon_code=Yii::$app->request->post('coupon_code');
			$model=Coupon::findOne(['code'=>$coupon_code,'status'=>1]);
			if($model){
				if(!$model->getUsedStatus(Yii::$app->user->getId())){
					throw new ErrorException('您已经领取过了！');
				}
				if(count($model->users)<$model->quantity){
					if($model->user_limit){
						$count=CustomerCoupon::find()->where(['customer_id'=>Yii::$app->user->getId(),'coupon_id'=>$model->coupon_id])->count();
						if($count<$model->user_limit){
							$customer_coupon=new CustomerCoupon();
							$customer_coupon->customer_id=Yii::$app->user->getId();
							$customer_coupon->coupon_id=$model->coupon_id;
							$customer_coupon->is_use=0;
							if ($model->date_type == 'DAYS') {
								$customer_coupon->start_time = date('Y-m-d H:i:s', time());
								$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $model->expire_seconds);
							} else {
								$customer_coupon->start_time = $model->date_start;
								$customer_coupon->end_time = $model->date_end;
							}
							$customer_coupon->date_added=date('Y-m-d H:i:s',time());
							if(!$customer_coupon->save(false)){
								throw new ErrorException("数据提交错误!");
							}
							$data= ['status' => 1, 'message' =>'领取成功！'];
						}else{
							throw new ErrorException("你已经领过了！");
						}
					}
				}else{
					throw new ErrorException("优惠券已被领光了!");
				}
			}else{
				throw new ErrorException("优惠券不存在或已过期");
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
    public function actionWxCoupon(){
	    if(!$url=\Yii::$app->request->get('redirect')){
		    if(Yii::$app->request->getReferrer()){
			    $url=Yii::$app->request->getReferrer();
		    }else{
			    $url=Url::to(['/site/index'],true);
		    }
	    }
	    if (\Yii::$app->user->isGuest) {
		    return $this->redirect(['/site/login','redirect'=>Url::to(['/coupon/wx-coupon','redirect'=>$url])]);
	    }
	    $codes=[
	    	//'ECP161210007','ECP161210008','ECP161210009','ECP161210012'
	    ];
	    $coupons=Coupon::find()->where(['code'=>$codes])->all();
	    if($coupons){
	    	foreach($coupons as $coupon){
	    		if(!$customer_coupon=CustomerCoupon::findOne(['coupon_id'=>$coupon->coupon_id])){
	    			$customer_coupon=new CustomerCoupon();
	    			$customer_coupon->customer_id=Yii::$app->user->getId();
	    			$customer_coupon->coupon_id=$coupon->coupon_id;
	    			$customer_coupon->description="双12优惠券";
	    			$customer_coupon->is_use=0;
				    if ($coupon->date_type == 'DAYS') {
					    $customer_coupon->start_time = date('Y-m-d H:i:s', time());
					    $customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $coupon->expire_seconds);
				    } else {
					    $customer_coupon->start_time = $coupon->date_start;
					    $customer_coupon->end_time = $coupon->date_end;
				    }
				    $customer_coupon->date_added=date('Y-m-d H:i:s',time());
				    $customer_coupon->save();
			    }
		    }
	    }
	    return $this->redirect($url);
    }
    public function actionCouponRules(){
        $this->layout = 'main_other';
        Yii::$app->session->set('new_guy_pop_nothing',1); //
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Url::to(['/coupon/coupon-rules','id'=>4])]);
        }
        $rules_id = Yii::$app->request->get('id');
        $coupon_rules = CouponRules::findOne(['coupon_rules_id'=>$rules_id]);
        $coupon_list = [];
        if($coupon_rules){
            $coupon_rules_details = CouponRulesDetail::find()->where(['coupon_rules_id'=>$rules_id])->all();
            if($coupon_rules_details){
                foreach ($coupon_rules_details as $coupon_rules_detail){
                    $coupon_list[] = $coupon_rules_detail->coupon;
                }
                return $this->render('coupon-rules',['coupon_rules_details'=>$coupon_rules_details,'coupon_rules'=>$coupon_rules]);
            }
        }



    }
    private function newGuyValiated($customer_id){
        $status = 0;
        if ($user_order = Order::find()->where(['customer_id' => $customer_id])->andWhere(["or", "order_status_id=2", "sent_to_erp='Y'"])->andWhere(["and","order_type_code <> 'GroundPush'"])->count("order_id")) {
            //有成功的订单 则不再发放新手券
            $status = 1; //不是新用户了
        }
        return $status;
    }
    public function actionApplyCouponRules(){
        $coupon_rules_detail_id = Yii::$app->request->post('detail_id');
        $action = Yii::$app->request->post('action');
        try{
            if(!$this->newGuyValiated(Yii::$app->user->getId())){
                if($coupon_rules_detail_id){
                    $coupon_rules_detail = CouponRulesDetail::findOne(['coupon_rules_detail_id'=>$coupon_rules_detail_id]);
                    if(!$coupon_rules_detail){
                        throw  new  Exception("数据错误，您申请的折扣券不存在");
                    }
                    $coupon = $coupon_rules_detail->coupon;
                    if(!$coupon){
                        throw  new  Exception("数据错误，您申请的折扣券不存在");
                    }

                    if(!$coupon->getUsedStatus(Yii::$app->user->getId())){
                        //已经领取，未使用的
                        throw  new  Exception("已经领取过了，请不要重复领取");
                    }else{
                        if(count($coupon->users) < $coupon->quantity){  //领取数量(包含所有使用，未使用，过期，未过期的) 不能超过 发行总量

                            if($coupon->user_limit){ //单个用户领取数量限制
                                $customer_coupon_count = CustomerCoupon::find()->where(['customer_id'=>Yii::$app->user->getId(),'coupon_id'=>$coupon->coupon_id])->count();
                                if($customer_coupon_count < $coupon->user_limit){
                                    if($this->couponRulesStatus($coupon_rules_detail->coupon_rules_id)){
                                        if($action == 'add'){
                                            if($this->addCustomerCoupon(Yii::$app->user->getId(),$coupon)){
                                                $data = ['status' => 2, 'message' => '申请成功'];
                                            }else{
                                                throw  new  Exception("领取时候发生错误，请联系客服");
                                            }
                                        }else{
                                            $data = ['status' => 1, 'message' => '领取成功'];
                                        }

                                    }else{
                                        throw  new  Exception("每个用户限领".$coupon_rules_detail->couponRules->user_total_limit.'次');
                                    }
                                }else{
                                    throw  new  Exception("您已经领取过了");
                                }
                            }else{
                                if($this->couponRulesStatus($coupon_rules_detail->coupon_rules_id)){
                                    if($action == 'add'){
                                        if($this->addCustomerCoupon(Yii::$app->user->getId(),$coupon)){
                                            $data = ['status' => 2, 'message' => '申请成功'];
                                        }else{
                                            throw  new  Exception("领取时候发生错误，请联系客服");
                                        }
                                    }else{
                                        $data = ['status' => 1, 'message' => '领取成功'];
                                    }


                                }else{
                                    throw  new  Exception("每个用户限领".$coupon_rules_detail->couponRules->user_total_limit.'个');
                                }

                            }
                        }else{
                            throw  new  Exception("该优惠券已经达到最大发行数量，无法继续领取");
                        }
                    }
                }else{
                    throw  new  Exception("请求错误");
                }
            }else{
                throw  new  Exception("该活动仅限新会员~");
            }


    } catch (Exception $e) {
            $data = ['status' => 0, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
    public function actionActionAdd(){
        $detail_id = Yii::$app->request->post('detail_id');

    }
    private function addCustomerCoupon($customer_id,$coupon){
        $customer_coupon=new CustomerCoupon();
        $customer_coupon->customer_id = $customer_id;
        $customer_coupon->coupon_id=$coupon->coupon_id;
        $customer_coupon->is_use=0;
        if ($coupon->date_type == 'DAYS') {
            $customer_coupon->start_time = date('Y-m-d H:i:s', time());
            $customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $coupon->expire_seconds);
        } else {
            $customer_coupon->start_time = $coupon->date_start;
            $customer_coupon->end_time = $coupon->date_end;
        }
        $customer_coupon->date_added=date('Y-m-d H:i:s',time());
        if(!$customer_coupon->save(false)){
            return false;
        }
        return true;
    }
    public function couponRulesStatus($coupon_rules_id){
        $coupon_rules  =  CouponRules::findOne(['coupon_rules_id'=>$coupon_rules_id]);
        $count = 0;
        $status = false;
        if($coupon_rules->details){
            foreach ($coupon_rules->details as $detail){
                $count = $count + $this->getUserCount(Yii::$app->user->getId(),$detail->coupon_id);
            }
        }
        if($count < $coupon_rules->user_total_limit){
            //满足coupont_rules，可以领取
            $status = true;
        }
        return $status;
    }
    private function getUserCount($customer_id, $coupon_id)
    {
        $count = 0;//该用户没有 该coupon

        if ($user_coupon = CustomerCoupon::find()->where(['customer_id' => $customer_id, 'coupon_id' => $coupon_id])->all()) {
            foreach ($user_coupon as $coupon) {
                if ( strtotime($coupon->end_time) > time()) { //未失效的
                    $count = $count + 1;
                }else{
                    //失效的
                    if($coupon->is_use == 1){
                        //且已经使用的
                        $count = $count + 1;
                    }
                }
            }
        }

        return $count;
    }
    public function actionRemovePop(){
        Yii::$app->session->remove('new_guy_pop_nothing');
    }


    public function getSubTotal(&$total_data, &$total, $cart)
    {
        $sub_total = 0;
        if ($cart) {
            foreach ($cart as $value) {
                $sub_total = bcadd($sub_total, $value->getPrice(), 2);
            }
        }
        $total_data[] = [
            'code' => 'sub_total',
            'title' => "商品总额",
            'value' => $sub_total,
            'sort_order' => 1
        ];
        $total = bcadd($total, $sub_total, 2);
    }

    public function getGlobalCouponTotal(&$total_data, &$total, $cart, $store_id, $shipping_cost = 0, &$coupon_gift,&$rate){
        $model = \api\models\V1\Coupon::find()->where(['status'=>1,'is_open' => 0,'is_prize'=>0,'is_entity'=>0,'store_id'=>$store_id])
            ->andWhere(["<=", "date_start", date('Y-m-d H:i:s', time())])
            ->andWhere([">=", "date_end", date('Y-m-d H:i:s', time())])
            ->orderBy('discount desc, date_end asc ,coupon_id desc')->groupBy('coupon_id')->all();
        if($model){

            foreach ($model as $coupon){
                $coupon_info=$this->getCoupon($coupon->coupon_id,$cart);

                if($coupon_info){
                    echo 3454;die;
                    $his_count=CouponHistory::find()->where(['coupon_id'=>$coupon_info->coupon_id,'customer_id'=>Yii::$app->user->getId()])->count();
                    if($coupon_info->user_limit && $his_count>=$coupon_info->user_limit){
                        continue;
                    }
                    $discount_total = 0;
                    $sub_total = 0;
                    $coupon_relate_product = [];
                    $coupon_relate_product_data = [];
                    if($this->order_product_paytotal&& isset($this->order_product_paytotal[$store_id])){
                        $rate = $this->order_product_paytotal[$store_id];
                    }
                    $sub_total_default_status = true;//默认为sub_total赋值 订单总额

                    if($coupon_info->except_category){
                        $sub_total_default_status = false;
                        $sub_total = 0;
                        $except_array = explode(',',$coupon_info->except_category);
                        if($except_array){
                            foreach ($cart as $value) {
                                if ( !in_array($value->product->productBase->category_id,$except_array)) {
                                    if (isset($rate) && $rate && isset($rate[$value->product->product_id])) {
                                        $sub_total = bcadd($sub_total, $rate[$value->product->product_id], 2);
                                    } else {
                                        $sub_total = bcadd($sub_total, $value->getCost(), 2);
                                    }
                                    $coupon_relate_product_data[] = $value;
                                }
                            }
                        }
                    }
                    if ($coupon_info->product) {
                        $sub_total_default_status = false;
                        //判断此券是否限制包装商品
                        $sub_total = 0;
                        $coupon_product = [];
                        foreach ($coupon_info->product as $value) {
                            if ($value->status) {
                                $coupon_product[] = $value->product_id;
                            }
                        }
                        if ($coupon_product) {
                            foreach ($cart as $val) {
                                if (in_array($val->product->product_id, $coupon_product)) {
                                    $coupon_relate_product[] = $val->product->product_id;
                                    $coupon_relate_product_data[] = $val;
                                    $sub_total += isset($rate[$val->product->product_id]) ? $rate[$val->product->product_id] : $val->getCost();
                                }
                            }
                        }
                    }
                    if($sub_total_default_status){
                        $sub_total = bcsub($total, $shipping_cost, 2); //默认值
                    }

                    if ($coupon_info->type == 'F') {
                        $discount_total = min($coupon_info->discount, $sub_total);
                    } elseif ($coupon_info['type'] == 'D') {
                        $discount_total = bcmul($sub_total, $coupon_info->discount, 2);
                    } elseif ($coupon_info['type'] == 'P') {
                        $discount_total = bcmul($sub_total, $coupon_info->discount, 2);
                    }
                    if ($coupon_info->min_discount && $coupon_info->min_discount > $discount_total) {
                        $discount_total = $coupon_info->min_discount;
                    }
                    if ($coupon_info->max_discount && $coupon_info->max_discount < $discount_total) {
                        $discount_total = $coupon_info->max_discount;
                    }
                    if ($coupon_info->shipping && $shipping_cost) {
                        $discount_total += $shipping_cost;
                    }
                    // If discount greater than total
                    if ($discount_total > $total) {
                        $discount_total = $total;
                    }
                    if ($discount_total >0) {
                        if ($coupon_relate_product_data) {
                            $group_rate = [];
                            $rate = [];
                            $rate_tmp = [];
                            foreach ($coupon_relate_product_data as $key => $product_data) {
                                $product_store_id = $product_data->product->store_id;
                                if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product_store_id])){
                                    $rate = $this->order_product_paytotal[$product_store_id];
                                }else{
                                    $rate = [];
                                }

                                $t_product_total = isset($rate[$product_data->product->product_id]) ? $rate[$product_data->product->product_id] : $product_data->getCost(); //获取当前该商品实付金额 $t_product_total
                                $t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10);
                                $t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
                                $group_rate[$product_data->product->product_id] = round($t_product_cost, 2);
                                $t_product_discount = round(bcsub($t_product_total,$t_product_cost,4), 2);
                                $rate_tmp[$product_data->product->product_id] = round($t_product_discount, 2); //该折扣券优惠的金额 $t_product_discount
                                $this->order_product_paytotal[$product_store_id][$product_data->product->product_id] = $t_product_total - $t_product_discount;//该商品当前实付金额

                            }
                        } else {
                            $order_rate = [];
                            $rate = [];
                            $rate_tmp = [];
                            foreach ($cart as $key => $product) {
                                $product_store_id = $product->product->store_id;
                                if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product_store_id])){
                                    $rate = $this->order_product_paytotal[$product_store_id];
                                }else{
                                    $rate = [];
                                }
                                $t_product_total = isset($rate[$product->product->product_id]) ? $rate[$product->product->product_id] : $product->getCost();
                                $t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10); //8折 X折优惠券 比率
                                $t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
                                $order_rate[$product->product->product_id] = round($t_product_cost, 2);
                                //$rate[$product->product->product_id] = round($t_product_cost, 2);
                                $t_product_discount = round(bcsub($t_product_total,$t_product_cost,4), 2);;//改折扣券 优惠的金额 $t_product_discount
                                $rate_tmp[$product->product->product_id] = round($t_product_discount, 2);
                                $this->order_product_paytotal[$product_store_id][$product->product->product_id] = $t_product_total - $t_product_discount;//该商品当前实付金额

                            }
                        }
                        $this->order_coupon_product_rate[$coupon_info->coupon_id] = $rate_tmp;
                    }
                    if ($coupon_info->gift) {
                        foreach ($coupon_info->gift as $gift) {
                            //if($gift->qty<=$gift->product->getStockCount()){
                            $coupon_gift[] = $gift;
                            //}
                        }
                    }
                    if($discount_total>0 || $coupon_info->gift){
                        $total_data[] = [
                            'code' => 'coupon',
                            'title' => $coupon_info->name,
                            'value' => -$discount_total,
                            'sort_order' => 3,
                            'code_id' => $coupon_info->coupon_id,
                            'customer_code_id' => 0
                        ];
                        $total -= $discount_total;
                    }
                    die;
                }
            }
        }
    }
    public function getCouponTotal(&$total_data, &$total, $cart, $customer_coupon_ids = [], $shipping_cost = 0, &$coupon_gift,&$rate)
    {
        if ($customer_coupon_ids = $this->FormartCouponIds($customer_coupon_ids)) {
            $coupon_array = [];
            foreach ($customer_coupon_ids as $customer_coupon_id) {
                //验证用户折扣券
                $model = CustomerCoupon::find()->where(['customer_coupon_id' => $customer_coupon_id, 'is_use' => 0])->andWhere(["<=", "start_time", date('Y-m-d H:i:s', time())])->andWhere([">=", "end_time", date('Y-m-d H:i:s', time())])->one();
                if(!$model){
                    continue;
                }

                //验证折扣券
                $coupon_info = $this->getCoupon($model->coupon_id, $cart);
                if ($coupon_info) {
                    $discount_total = 0;
                    $sub_total = 0;
                    $coupon_relate_product = [];
                    $coupon_relate_product_data = [];
                    $sub_total_default_status = true;//默认为sub_total赋值 订单总额

                    if($coupon_info->except_category){
                        $sub_total_default_status = false;
                        $sub_total = 0;
                        $except_array = explode(',',$coupon_info->except_category);
                        if($except_array){
                            foreach ($cart as $value) {
                                if ( !in_array($value->product->productBase->category_id,$except_array)) {
                                    if (isset($this->order_product_paytotal[$value->product->store_id]) && $this->order_product_paytotal[$value->product->store_id] && isset($this->order_product_paytotal[$value->product->store_id][$value->product->product_id])) {
                                        $sub_total = bcadd($sub_total, $this->order_product_paytotal[$value->product->store_id][$value->product->product_id], 2);
                                    } else {
                                        $sub_total = bcadd($sub_total, $value->getCost(), 2);
                                    }
                                    $coupon_relate_product_data[] = $value;
                                }
                            }
                        }
                    }


                    if ($coupon_info->product) {
                        $sub_total_default_status = false;
                        //判断此券是否限制包装商品
                        $sub_total = 0;
                        $coupon_product = [];
                        foreach ($coupon_info->product as $value) {
                            if ($value->status) {
                                $coupon_product[] = $value->product_id;
                            }
                        }
                        if ($coupon_product) {
                            foreach ($cart as $val) {
                                if (in_array($val->product->product_id, $coupon_product)) {
                                    $coupon_relate_product[] = $val->product->product_id;
                                    $coupon_relate_product_data[] = $val;
                                    $sub_total += isset($this->order_product_paytotal[$val->product->store_id][$val->product->product_id]) ? $this->order_product_paytotal[$val->product->store_id][$val->product->product_id] : $val->getCost();
                                }
                            }
                        }
                    }
                    if($sub_total_default_status){
                        $sub_total = bcsub($total, $shipping_cost, 2); //默认值
                    }
                    if($sub_total > 0){
                        if ($coupon_info->type == 'F') {
                            $discount_total = min($coupon_info->discount, $sub_total);
                        } elseif ($coupon_info['type'] == 'D') {
                            $discount_total = bcmul($sub_total, $coupon_info->discount, 2);
                        } elseif ($coupon_info['type'] == 'P') {
                            $discount_total = bcmul($sub_total, $coupon_info->discount, 2);
                        }
                    }else{
                        $discount_total = 0;
                    }

                    if ($coupon_info->min_discount && $coupon_info->min_discount > $discount_total) {
                        $discount_total = $coupon_info->min_discount;
                    }
                    if ($coupon_info->max_discount && $coupon_info->max_discount < $discount_total) {
                        $discount_total = $coupon_info->max_discount;
                    }
                    if ($coupon_info->shipping && $shipping_cost) {
                        $discount_total += $shipping_cost;
                    }
                    // If discount greater than total
                    if ($discount_total > $total) {
                        $discount_total = $total;
                    }
                    if ($discount_total >0) {
                        if ($coupon_relate_product_data) {
                            $group_rate = [];
                            $rate = [];
                            $rate_tmp = [];
                            foreach ($coupon_relate_product_data as $key => $product_data) {
                                if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product_data->store_id])){
                                    $rate = $this->order_product_paytotal[$product_data->store_id];
                                }else{
                                    $rate = [];
                                }

                                $t_product_total = isset($rate[$product_data->product->product_id]) ? $rate[$product_data->product->product_id] : $product_data->getCost(); //获取当前该商品实付金额 $t_product_total
                                $t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10);
                                $t_product_cost = round(bcmul($t_product_rate, $t_product_total, 10),2);
                                $group_rate[$product_data->product->product_id] = round($t_product_cost, 2);
                                $t_product_discount = round(bcsub($t_product_total,$t_product_cost,4), 2);
                                $rate_tmp[$product_data->product->product_id] = $t_product_discount; //该折扣券优惠的金额 $t_product_discount
                                $this->order_product_paytotal[$product_data->store_id][$product_data->product->product_id] = $t_product_total - $t_product_discount;//该商品当前实付金额

                            }
                        } else {
                            $order_rate = [];
                            $rate = [];
                            $rate_tmp = [];
                            foreach ($cart as $key => $product) {
                                if($this->order_product_paytotal&& isset($this->order_product_paytotal[$product->store_id])){
                                    $rate = $this->order_product_paytotal[$product->store_id];
                                }else{
                                    $rate = [];
                                }

                                $t_product_total = isset($rate[$product->product->product_id]) ? $rate[$product->product->product_id] : $product->getCost();
                                $t_product_rate = bcdiv(bcsub($sub_total, $discount_total, 2), $sub_total, 10); //8折 X折优惠券 比率
                                $t_product_cost = bcmul($t_product_rate, $t_product_total, 10);
                                $order_rate[$product->product->product_id] = round($t_product_cost, 2);
                                $t_product_discount = round(bcsub($t_product_total,$t_product_cost,4), 2);//该折扣券 优惠的金额 $t_product_discount
                                $rate_tmp[$product->product->product_id] = $t_product_discount;
                                $this->order_product_paytotal[$product->store_id][$product->product->product_id] = $t_product_total - $t_product_discount;//该商品当前实付金额

                            }
                        }
                        $this->order_coupon_product_rate[$coupon_info->coupon_id] = $rate_tmp;
                    }
                    if ($coupon_info->gift) {
                        foreach ($coupon_info->gift as $gift) {
                            //if($gift->qty<=$gift->product->getStockCount()){
                            $coupon_gift[] = $gift;
                            //}
                        }
                    }
                    if($discount_total>0 || $coupon_info->gift){

                        $total_data[] = [
                            'code' => 'coupon',
                            'title' => $coupon_info->name,
                            'value' => -$discount_total,
                            'sort_order' => 3,
                            'code_id' => $coupon_info->coupon_id,
                            'customer_code_id' => $customer_coupon_id
                        ];
                        $total -= $discount_total;
                    }
                    $coupon_array[] = $customer_coupon_id;
                    //Yii::$app->session->set('customer_use_coupon_h5',$array);
                }
            }
            return $coupon_array;
        }
    }

    public function getShippingTotal(&$total_data, &$total, $cart, $store_id = 0, &$shipping_cost, $delivery_station_id = 0)
    {
        //进行运费计算
        $sub_total = 8;
        $total_fee = $total;
//        if($cart){
//            foreach($cart as $value){
//                if($value->product_id=='22189'){
//                    $total_fee=$total_fee-200;
//                    break;
//                }
//            }
//        }
        //判断是否满足店铺内包邮
        if ($store = Store::findOne(['store_id' => $store_id])) {
            //是否包邮（满X元包邮）
            if ($store->befreepostage == 1 && $store->minbookcash <= $total_fee) {
                $sub_total = 0;
            }
        }
        //判断商品是否为包邮商品或一元购商品
        if ($cart) {
            foreach ($cart as $value) {
                if ($value->product->baoyou) { //包邮活动
                    $sub_total = 0;
                    break;
                }
//				if ($value->promotion && $value->promotion->promotion->subject == 'YIYUANGOU') { //一元免邮
//					$sub_total = 0;
//					break;
//				}
            }
        }
        //判断是否使用自提点，若使用自己提供的自提点，免运费
        if ($delivery_station_id) {
            if ($deliverObj = PlatformStation::findOne(['id' => $delivery_station_id])) {
                if ($deliverObj->store_id == $store_id) {
                    $sub_total = 0;
                }
            }
        }
        $shipping_cost = $sub_total;
        $total_data[] = [
            'code' => 'shipping',
            'title' => "固定运费",
            'value' => $sub_total,
            'sort_order' => 2
        ];
        $total += $sub_total;
    }

    private function setTotalsData($title,$code,$value,$sort,$params=null){
        $data = [
            'code' => $code,
            'title' => $title,
            'value' => $value,
            'sort_order' => $sort
        ];
        if($params){
            foreach ($params as $key => $value){
                $data[$key] = $value;
            }
        }

        return $data;
    }

    public function getCoupon($coupon_id,$order_cart){
        $status = true;
        if($coupon=Coupon::findOne(['coupon_id'=>$coupon_id,'status'=>1])){
            $sub_qty = 0; //统计购物数量
            $sub_total = 0;//统计商品小计
            $sub_total_default_status = true;
            //判断此券是否限制包装商品
            if ($coupon->product) {
                $sub_total_default_status = false;
                $coupon_product = [];
                foreach ($coupon->product as $value) {
                    if ($value->status) {
                        $coupon_product[] = $value->product_id;
                    }
                }
                foreach ($order_cart as $value) {
                    if (in_array($value->product->product_id, $coupon_product) ) {
                        $sub_qty += $value->quantity;
                        if (isset($this->order_coupon_product_rate[$coupon_id]) && $this->order_coupon_product_rate[$coupon_id] && isset($this->order_coupon_product_rate[$coupon_id][$value->product->product_id])) {
                            $sub_total = bcadd($sub_total, $this->order_coupon_product_rate[$coupon_id][$value->product->product_id], 2);
                        } else {
                            $sub_total = bcadd($sub_total, $value->getCost(), 2);
                        }
                    }
                }
            }

            if($coupon->except_category){
                $sub_total_default_status = false;
                $except_array = explode(',',$coupon->except_category);
                foreach ($order_cart as $value) {
                    if ( !in_array($value->product->productBase->category_id,$except_array)) {
                        $sub_qty += $value->quantity;
                        if (isset($this->order_coupon_product_rate[$coupon_id]) && $this->order_coupon_product_rate[$coupon_id] && isset($this->order_coupon_product_rate[$coupon_id][$value->product->product_id])) {
                            $sub_total = bcadd($sub_total, $this->order_coupon_product_rate[$coupon_id][$value->product->product_id], 2);
                        } else {
                            $sub_total = bcadd($sub_total, $value->getCost(), 2);
                        }
                    }
                }
            }

            if($sub_total_default_status){
                $sub_qty = $this->SubQty($order_cart); //统计购物数量
                $sub_total = $this->SubTotal($order_cart);//统计商品小计
            }
            if($sub_total <= 0){
                $status = false;
            }
            if ($coupon->limit_min_quantity && $coupon->limit_min_quantity > $sub_qty) {
                $status = false;
            }
            if ($coupon->limit_max_quantity && $coupon->limit_max_quantity < $sub_qty) {
                $status = false;
            }
            if ($coupon->total > 0 && $coupon->total > $sub_total) {
                $status = false;
            }
            if ($coupon->limit_max_total > 0 && $coupon->limit_max_total < $sub_total) {
                $status = false;
            }
            //判断券的使用次数
            $count = count($coupon->history);
            if ($coupon->quantity > 0 && $count >= $coupon->quantity) {
                $status = false;
            }
        }
        if ($status) {
            return $coupon;
        }else{
            return null;
        }
    }
}
