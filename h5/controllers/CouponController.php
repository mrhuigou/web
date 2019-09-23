<?php

namespace h5\controllers;
use api\models\V1\Coupon;
use api\models\V1\CouponCate;
use api\models\V1\CouponRules;
use api\models\V1\CouponRulesDetail;
use api\models\V1\Order;
use api\models\V1\Product;
use common\component\Track\Track;
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


}
