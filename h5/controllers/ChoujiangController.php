<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/22
 * Time: 15:26
 */
namespace h5\controllers;
use api\models\V1\CustomerCoupon;
use api\models\V1\Lottery;
use api\models\V1\LotteryPrize;
use api\models\V1\LotteryResult;
use api\models\V1\CouponRules;
use api\models\V1\CouponRulesDetail;
use api\models\V1\Order;
use api\models\V1\WeixinScans;
use api\models\V1\WeixinScansNews;
use common\component\image\Image;
use common\component\Notice\WxNotice;
use common\component\Wx\WxScans;
use common\models\User;
use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class ChoujiangController extends \yii\web\Controller {
	public function actionIndex($id = 42)
	{
        $lottery_id = \Yii::$app->request->get('id');
        if($lottery_id){
            $id = $lottery_id;
        }
		$this->layout = "main_other";
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
//		if(!\Yii::$app->user->identity->getSubcription()){
//			return $this->redirect('/');
//		}
        $lottery = Lottery::findOne(['id'=>$id]);
		$model = LotteryResult::find()->where(['lottery_id' => $id])->andWhere(['not in','customer_id',[\Yii::$app->user->getId()]]);
		$count = $model->count();
		$history = $model->limit(100)->orderBy('id desc')->all();
		$my_self = LotteryResult::find()->where(['lottery_id' => $id, 'customer_id' => \Yii::$app->user->getId()])->all();
		return $this->render('index', ['id' => $id, 'history' => $history, 'count' => $count, 'my_self' => $my_self,'lottery'=>$lottery]);
//		return $this->render('index-new', ['id' => $id, 'history' => $history, 'count' => $count, 'my_self' => $my_self]);
	}
//    public function actionIndexNew($id = 41)
//    {
//        $lottery_id = \Yii::$app->request->get('id');
//        if($lottery_id){
//            $id = $lottery_id;
//        }
//        $this->layout = "main_other";
//        if (\Yii::$app->user->isGuest) {
//            return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
//        }
////		if(!\Yii::$app->user->identity->getSubcription()){
////			return $this->redirect('/');
////		}
//        $model = LotteryResult::find()->where(['lottery_id' => $id])->andWhere(['not in','customer_id',[\Yii::$app->user->getId()]]);
//        $count = $model->count();
//        $history = $model->limit(100)->orderBy('id desc')->all();
//        $my_self = LotteryResult::find()->where(['lottery_id' => $id, 'customer_id' => \Yii::$app->user->getId()])->all();
////        return $this->render('index', ['id' => $id, 'history' => $history, 'count' => $count, 'my_self' => $my_self]);
//
//        //查询所有的优惠券
//        $coupon_rules_id = 5;
//        $coupon_rules=CouponRules::findOne(['coupon_rules_id'=>$coupon_rules_id]);
//        $coupon_info=CouponRulesDetail::find()->where(['coupon_rules_id'=>$coupon_rules->coupon_rules_id])->all();
//
//		return $this->render('index-new', ['id' => $id, 'history' => $history, 'count' => $count, 'my_self' => $my_self ,'coupon_info' => $coupon_info]);
//    }

    public function actionIndexNew($id = 42,$coupon_rules_id = 5)
    {
        $lottery_id = \Yii::$app->request->get('id');
        $coupon_rules_id1 = \Yii::$app->request->get('coupon_rules_id');//优惠券规则id
        if($lottery_id){
            $id = $lottery_id;
        }
        if($coupon_rules_id1){
            $coupon_rules_id = $coupon_rules_id1;
        }
        $this->layout = "main_other";
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
//		if(!\Yii::$app->user->identity->getSubcription()){
//			return $this->redirect('/');
//		}
        $model = LotteryResult::find()->where(['lottery_id' => $id])->andWhere(['not in','customer_id',[\Yii::$app->user->getId()]]);
        $count = $model->count();
        $history = $model->limit(100)->orderBy('id desc')->all();
        $my_self = LotteryResult::find()->where(['lottery_id' => $id, 'customer_id' => \Yii::$app->user->getId()])->all();
//        return $this->render('index', ['id' => $id, 'history' => $history, 'count' => $count, 'my_self' => $my_self]);

        //查询所有的优惠券
        $coupon_rules=CouponRules::findOne(['coupon_rules_id'=>$coupon_rules_id]);
        $coupon_info=CouponRulesDetail::find()->where(['coupon_rules_id'=>$coupon_rules->coupon_rules_id])
            ->orderBy('coupon_rules_detail_id desc')
            ->orderBy('sort desc')
            ->all();

        //对优惠券进行时间过滤处理
        foreach ($coupon_info as $key => &$value){
            if(!$value->coupon_id){
                unset($coupon_info[$key]);
                continue;//优惠券不存在
            }
            if($value->coupon->date_end < date('Y-m-d H:i:s')){
                unset($coupon_info[$key]);
                continue;//优惠券过期
            }
        }

        return $this->render('index-new', ['id' => $id, 'history' => $history, 'count' => $count, 'my_self' => $my_self ,'coupon_info' => $coupon_info]);
    }



    public function actionCommon($id=40)
    {
        $this->layout = "main_other";
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
        if(!\Yii::$app->user->identity->getSubcription()){
            return $this->redirect('/');
        }
        $lottery = Lottery::findOne(['id'=>$id]);
        if($lottery){
            $model = LotteryResult::find()->where(['lottery_id' => $id])->andWhere(['not in','customer_id',[\Yii::$app->user->getId()]]);
            $count = $model->count();
            $history = $model->limit(100)->orderBy('id desc')->all();
            $my_self = LotteryResult::find()->where(['lottery_id' => $id, 'customer_id' => \Yii::$app->user->getId()])->all();
            return $this->render('common', ['id' => $id, 'history' => $history, 'count' => $count, 'my_self' => $my_self,'lottery'=>$lottery]);
        }else{
            throw new NotFoundHttpException("抽奖活动不存在或已经过期");
        }

    }
    public function actionBaby($id=31){
       // $this->layout = "main_other";
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
        if(!\Yii::$app->user->identity->getSubcription()){
            return $this->redirect('/');
        }
        $lottery = Lottery::findOne(['id'=>$id]);
        if($lottery){
            $model = LotteryResult::find()->where(['lottery_id' => $id])->andWhere(['not in','customer_id',[\Yii::$app->user->getId()]]);
            $count = $model->count();
            $history = $model->limit(100)->orderBy('id desc')->all();
            $my_self = LotteryResult::find()->where(['lottery_id' => $id, 'customer_id' => \Yii::$app->user->getId()])->all();
            return $this->render('baby', ['id' => $id, 'history' => $history, 'count' => $count, 'my_self' => $my_self,'lottery'=>$lottery]);
        }else{
            throw new NotFoundHttpException("抽奖活动不存在或已经过期");
        }
    }

	public function actionApply()
	{
		try {
			if (\Yii::$app->user->isGuest) {
				throw new ErrorException('您还没有登录');
			}
			if (!$lottery_id = \Yii::$app->request->post('id')) {
				throw new ErrorException('参数错误');
			}
			if (!$lottery = Lottery::findOne($lottery_id)) {
				throw new ErrorException('活动不存在');
			}

			if (strtotime($lottery->start_time) > time()) {
				throw new ErrorException('抽奖活动暂未开始，稍后在试！');
			}
			if (strtotime($lottery->end_time) < time()) {
				throw new ErrorException('抽奖活动已经结束！');
			}
            if(!$lottery->chances_per_customer ){
                $chances_per_customer = 1;
            }else{
                $chances_per_customer = $lottery->chances_per_customer;
            }
            $result_count = LotteryResult::find()->where(['lottery_id' => $lottery_id, 'customer_id' => \Yii::$app->user->getId()])->count();
            if($result_count >= $chances_per_customer){
                throw  new ErrorException("您的抽奖机会已经用完！");
            }
			if ($lottery_count = LotteryResult::find()->where(['lottery_id' => $lottery_id, 'customer_id' => \Yii::$app->user->getId()])->andWhere(['>=','creat_at',strtotime(date('Y-m-d',time()))])->andWhere(['<=','creat_at',strtotime(date('Y-m-d',time())) + 24*60*60 - 1])->count()) {
				if($lottery_count>=1){
					throw new ErrorException('您已经抽过了,请明日再参加抽奖!');
				}
			}
			if (!$prize_box = LotteryPrize::find()->where(['lottery_id' => $lottery_id])->all()) {
				throw new ErrorException('当前活动没有设置奖品');
			}
            $prize_relust_count = LotteryResult::find()->where(['lottery_id' => $lottery_id])->count();
            shuffle($prize_box); //打乱数组顺序
            $arr = [];
            foreach ($prize_box as $key => $val) {
                if(!$val->quantity){
                    $base_quantity = 0;
                }else{
                    $base_quantity = $val->quantity;
                }
                if($base_quantity <= $prize_relust_count){
                    $arr[$val->id] = $val->probability;
                }
            }

            $rid = $this->get_rand($arr); //根据概率获取奖项id
//
//			$order_count=Order::find()->where(['customer_id'=>\Yii::$app->user->getId(),'sent_to_erp'=>'Y'])->count();
//			if(!$lottery_count){
//				if($order_count==1){
//					$rid=85;
//				}elseif($order_count==2){
//					$rid=82;
//				}elseif ($order_count>=3 && $order_count<=5){
//					$rid=79;
//				}elseif ($order_count>=6 && $order_count<=9){
//					$rid=76;
//				}else{
//					$rid=73;
//				}
//			}else{
//				if($order_count<3){
//					$rid=91;
//				}else{
//					$rid=88;
//				}
//			}
			if ($result = LotteryPrize::findOne($rid)) {
				$model = new LotteryResult();
				$model->lottery_id = $lottery_id;
				$model->customer_id = \Yii::$app->user->getId();
				$model->lottery_prize_id = $rid;
				$model->creat_at = time();
				$model->save();
				if ($result->coupon) {
					$customer_coupon = new CustomerCoupon();
					$customer_coupon->customer_id = \Yii::$app->user->getId();
					$customer_coupon->coupon_id = $result->coupon->coupon_id;
					$customer_coupon->description = "抽奖获得";
					$customer_coupon->from_lottery_result_id = $model->id;
					$customer_coupon->is_use = 0;
					if ($result->coupon->date_type == 'DAYS') {
						$customer_coupon->start_time = date('Y-m-d H:i:s', time());
						$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $result->coupon->expire_seconds);
					} else {
						$customer_coupon->start_time = $result->coupon->date_start;
						$customer_coupon->end_time = $result->coupon->date_end;
					}
					$customer_coupon->date_added = date('Y-m-d H:i:s', time());
					$customer_coupon->save();
				}
                $data = ['status' => 1, 'angle' => $result->angle,'title'=>$result->title,'description'=>$result->description, 'message' => '恭喜您获得' . $result->title."红包优惠券,".$result->description."！"];
				$message[]=[
					'customer_id'=>\Yii::$app->user->getId(),
					'url'=>Url::to(['/user-coupon/index'],true),
					'content'=>['title'=>'亲，恭喜您获得'.$result->title."红包优惠券！",'name'=>'抽奖活动','content'=>'已经存入你的个人帐户。'],
				];
				$this->sendMessage($message);
			} else {
				throw new ErrorException('网络超时,请重试！');
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}

    /**
     * @desc申请抽奖新
     */
    public function actionApplyNew()
    {

        try {
            if (\Yii::$app->user->isGuest) {
                throw new ErrorException('您还没有登录');
            }
            if (!$lottery_id = \Yii::$app->request->post('id')) {
                throw new ErrorException('参数错误');
            }
            if (!$lottery = Lottery::findOne($lottery_id)) {
                throw new ErrorException('活动不存在');
            }

            if (strtotime($lottery->start_time) > time()) {
                throw new ErrorException('抽奖活动暂未开始，稍后在试！');
            }
            if (strtotime($lottery->end_time) < time()) {
                throw new ErrorException('抽奖活动已经结束！');
            }
            if(!$lottery->chances_per_customer ){
                $chances_per_customer = 1;
            }else{
                $chances_per_customer = $lottery->chances_per_customer;
            }

            $result_count = LotteryResult::find()->where(['lottery_id' => $lottery_id, 'customer_id' => \Yii::$app->user->getId()])->count();

            if($result_count >= $chances_per_customer){
                throw  new ErrorException("您的抽奖机会已经用完！");
            }
            if ($lottery_count = LotteryResult::find()->where(['lottery_id' => $lottery_id, 'customer_id' => \Yii::$app->user->getId()])->count()) {
                if($lottery_count>=1){
                    throw new ErrorException('您已经抽过了');
                }
            }
            //判断今天是否已经抽奖
//            $day_result_count = LotteryResult::find()
//                ->where(['lottery_id' => $lottery_id, 'customer_id' => \Yii::$app->user->getId()])
//                ->andWhere(['>=','creat_at',strtotime(date("Y-m-d"),time())])
//                ->andWhere(['<=','creat_at',strtotime(date("Y-m-d"),time()) + 86399])
//                ->count();
////
//            if($day_result_count >=1){
//                throw new ErrorException('今天已经参与抽奖活动');
//            }

            if (!$prize_box = LotteryPrize::find()->where(['lottery_id' => $lottery_id])->all()) {
                throw new ErrorException('当前活动没有设置奖品');
            }
            $prize_relust_count = LotteryResult::find()->where(['lottery_id' => $lottery_id])->count();
            shuffle($prize_box); //打乱数组顺序
            $arr = [];
            foreach ($prize_box as $key => $val) {
                if(!$val->quantity){
                    $base_quantity = 0;
                }else{
                    $base_quantity = $val->quantity;
                }
                if($base_quantity <= $prize_relust_count){
                    $arr[$val->id] = $val->probability;
                }
            }

            $rid = $this->get_rand($arr); //根据概率获取奖项id
//
//			$order_count=Order::find()->where(['customer_id'=>\Yii::$app->user->getId(),'sent_to_erp'=>'Y'])->count();
//			if(!$lottery_count){
//				if($order_count==1){
//					$rid=85;
//				}elseif($order_count==2){
//					$rid=82;
//				}elseif ($order_count>=3 && $order_count<=5){
//					$rid=79;
//				}elseif ($order_count>=6 && $order_count<=9){
//					$rid=76;
//				}else{
//					$rid=73;
//				}
//			}else{
//				if($order_count<3){
//					$rid=91;
//				}else{
//					$rid=88;
//				}
//			}
            if ($result = LotteryPrize::findOne($rid)) {
                $model = new LotteryResult();
                $model->lottery_id = $lottery_id;
                $model->customer_id = \Yii::$app->user->getId();
                $model->lottery_prize_id = $rid;
                $model->creat_at = time();
                $model->save();
                if ($result->coupon) {
                    $customer_coupon = new CustomerCoupon();
                    $customer_coupon->customer_id = \Yii::$app->user->getId();
                    $customer_coupon->coupon_id = $result->coupon->coupon_id;
                    $customer_coupon->description = "抽奖获得";
                    $customer_coupon->is_use = 0;
                    if ($result->coupon->date_type == 'DAYS') {
                        $customer_coupon->start_time = date('Y-m-d H:i:s', time());
                        $customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $result->coupon->expire_seconds);
                    } else {
                        $customer_coupon->start_time = $result->coupon->date_start;
                        $customer_coupon->end_time = $result->coupon->date_end;
                    }
                    $customer_coupon->date_added = date('Y-m-d H:i:s', time());
                    $customer_coupon->save();

                    //$data = ['status' => 1, 'angle' => $result->angle,'title'=>$result->title,'description'=>$result->description, 'message' => '恭喜您获得' . $result->title."红包优惠券,".$result->description."！"];
                    $data = ['status' => 1, 'angle' => $result->angle,'title'=>$result->title,'description'=>$result->description, 'message' => '恭喜您获得' . $result->title.'红包优惠券,请于'.date("m月d",strtotime($customer_coupon->start_time)).'号开始使用!'];
                    $message[]=[
                        'customer_id'=>\Yii::$app->user->getId(),
                        'url'=>Url::to(['/user-coupon/index'],true),
                        'content'=>['title'=>'亲，恭喜您获得优惠券！请于'.date("m月d",strtotime($customer_coupon->start_time)).'号开始使用','name'=>$result->title,'content'=>$result->title.'已经存入你的个人帐户。'],
                    ];
                    $this->sendMessage($message);
                }

            } else {
                throw new ErrorException('网络超时,请重试！');
            }
        } catch (ErrorException $e) {
            $data = ['status' => 0, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionApplyBaby(){
        try {
            if (\Yii::$app->user->isGuest) {
                throw new ErrorException('您还没有登录');
            }
            if (!$lottery_id = \Yii::$app->request->post('id')) {
                throw new ErrorException('参数错误');
            }
            if (!$lottery = Lottery::findOne($lottery_id)) {
                throw new ErrorException('活动不存在');
            }
            if (strtotime($lottery->start_time) > time()) {
                throw new ErrorException('抽奖活动暂未开始，稍后在试！');
            }
            if (strtotime($lottery->end_time) < time()) {
                throw new ErrorException('抽奖活动已经结束！');
            }
            if ($lottery_count = LotteryResult::find()->where(['lottery_id' => $lottery_id, 'customer_id' => \Yii::$app->user->getId()])->count()) {
                if($lottery_count>=1){
                    throw new ErrorException('您已经抽过了');
                }
            }

            if (!$prize_box = LotteryPrize::find()->where(['lottery_id' => $lottery_id])->orderBy('angle')->all()) {
                throw new ErrorException('当前活动没有设置奖品');
            }
            $list = [];
            foreach ($prize_box as $key => $val) {
                $list[$val->id] = $key;
            }
            shuffle($prize_box); //打乱数组顺序
            $arr = [];
            foreach ($prize_box as $key => $val) {
                $arr[$val->id] = $val->probability;
            }

            $rid = $this->get_rand($arr); //根据概率获取奖项id

            if ($result = LotteryPrize::findOne($rid)) {
                $model = new LotteryResult();
                $model->lottery_id = $lottery_id;
                $model->customer_id = \Yii::$app->user->getId();
                $model->lottery_prize_id = $rid;
                $model->creat_at = time();
                $model->save();
                if ($result->coupon) {
                    $customer_coupon = new CustomerCoupon();
                    $customer_coupon->customer_id = \Yii::$app->user->getId();
                    $customer_coupon->coupon_id = $result->coupon->coupon_id;
                    $customer_coupon->description = "抽奖获得";
                    $customer_coupon->is_use = 0;
                    if ($result->coupon->date_type == 'DAYS') {
                        $customer_coupon->start_time = date('Y-m-d H:i:s', time());
                        $customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $result->coupon->expire_seconds);
                    } else {
                        $customer_coupon->start_time = $result->coupon->date_start;
                        $customer_coupon->end_time = $result->coupon->date_end;
                    }
                    $customer_coupon->date_added = date('Y-m-d H:i:s', time());
                    $customer_coupon->save();
                }
               // $data = ['status' => 1, 'angle' => $result->angle,'title'=>$result->title,'description'=>$result->description, 'message' => '恭喜您获得' . $result->title."红包优惠券,".$result->description."！"];
                $data = ['status' => 1, 'prize' => $list[$rid], 'customer_coupon_id' => $customer_coupon->customer_coupon_id, 'name' => $result->coupon->name, 'description' => $result->coupon->description];
//                $message[]=[
//                    'customer_id'=>\Yii::$app->user->getId(),
//                    'url'=>Url::to(['/user-coupon/index'],true),
//                    'content'=>['title'=>'亲，恭喜您获得'.$result->title."红包优惠券！",'name'=>'抽奖活动','content'=>'已经存入你的个人帐户。'],
//                ];
//                $this->sendMessage($message);
            } else {
                throw new ErrorException('网络超时,请重试！');
            }
        } catch (ErrorException $e) {
            $data = ['status' => 0, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

	public function actionResult()
	{
		$lottery_id = \Yii::$app->request->post('lottery_id');
		$data = [];
//		$model = LotteryResult::findOne(['lottery_id' => $lottery_id, 'customer_id' => \Yii::$app->user->getId()]);
		$model = LotteryResult::find()->where(['lottery_id' => $lottery_id, 'customer_id' => \Yii::$app->user->getId()])->andWhere(['>=','creat_at',strtotime(date('Y-m-d',time()))])->andWhere(['<=','creat_at',strtotime(date('Y-m-d',time())) + 24*60*60 - 1])->one();
		if ($model) {

            $datetime = date('m/d H:i:s', $model->creat_at);
		    //抽奖结果======================日期显示
            $LotteryPrize = LotteryPrize::findOne(['id' => $model->lottery_prize_id]);
            $coupon_id = $LotteryPrize->coupon_id;
            $customer_id = \Yii::$app->user->getId();
            $customer_coupon_info = \api\models\V1\CustomerCoupon::find()->where(['customer_id'=>$customer_id,'coupon_id'=>$coupon_id,'from_lottery_result_id'=>$model->id])->orderBy('customer_coupon_id desc')->one();

            if($customer_coupon_info){
                if($customer_coupon_info->is_use == 2){
                    $datetime = '已使用';
                }elseif($customer_coupon_info->is_use == 0 && $customer_coupon_info->end_time <= date('Y-m-d H:i:s')){
                    $datetime= "已过期";
                }else{
                    $datetime = "截止：".date('m-d',strtotime($customer_coupon_info->start_time))."~".date('m-d H:i',strtotime($customer_coupon_info->end_time));
                }
            }
            //抽奖结果======================日期显示

			$data = [
				'id' => $model->id,
				'nickname' => $model->customer->nickname,
				'photo' => Image::resize($model->customer->photo, 100, 100),
//				'datetime' => date('m/d H:i:s', $model->creat_at),
				'datetime' => $datetime,
				'des' => $model->prize->title,
			];
		}
		\Yii::$app->getResponse()->format = "json";
		return ['data' => $data];
	}

	protected function get_rand($proArr)
	{
		$result = '';
		//概率数组的总概率精度
		$proSum = array_sum($proArr);
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);
		return $result;
	}
	protected function sendMessage($message = [])
	{
		if ($message) {
			foreach ($message as $value) {
				if ($user = User::findIdentity($value['customer_id'])) {
					if ($open_id = $user->getWxOpenId()) {
						$notice = new WxNotice();
						$notice->zhongjiang($open_id, $value['url'], $value['content']);
					}
				}
			}
		}
	}
}