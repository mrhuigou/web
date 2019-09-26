<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/14
 * Time: 19:09
 */
namespace h5\controllers;
use api\models\V1\Coupon;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerHongbao;
use api\models\V1\Exercise;
use api\models\V1\ExerciseHistory;
use api\models\V1\ExerciseRule;
use api\models\V1\ExerciseRuleCoupon;
use api\models\V1\Lottery;
use api\models\V1\LotteryPrize;
use api\models\V1\LotteryResult;
use api\models\V1\Order;
use api\models\V1\PrizeBox;
use api\models\V1\PrizeBoxHistory;
use api\models\V1\PrizeChance;
use common\component\Notice\WxNotice;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;

class GameController extends \yii\web\Controller {
    private $lottery_id = 34;
	public function actionIndex()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => '/game/index']);
		}
		$lottery = Lottery::findOne(['id'=>$this->lottery_id]);
		//print_r($model);exit;
		if (($model = LotteryPrize::find()->where(['lottery_id' => $lottery->id])->limit(8)->orderBy('angle asc')->all()) && count($model) == 8) {
			return $this->render('index', ['model' => $model,'lottery'=>$lottery]);
		} else {
			throw new NotFoundHttpException("当前没有任何数据");
		}
	}
	public function actionApplyLottery()
	{
		try {
			if (\Yii::$app->user->isGuest) {
				throw new ErrorException('你还没有登陆，请先登陆！', 101);
			}
			if($model=Lottery::findOne(['id'=>$this->lottery_id])){

				if(strtotime($model->start_time)>time()){
					throw  new ErrorException("活动暂未开始！");
				}
				if(strtotime($model->end_time)<time()){
					throw  new ErrorException("活动已经结束！");
				}
                if(!$model->chances_per_customer ){
                    $chances_per_customer = 1;
                }else{
                    $chances_per_customer = $model->chances_per_customer;
                }
                $result_count = LotteryResult::find()->where(['customer_id'=>\Yii::$app->user->getId(),'lottery_id'=>$this->lottery_id])->count();
                if($result_count >= $chances_per_customer){
                    throw  new ErrorException("您的抽奖机会已经用完！");
                }
			}else{
				throw  new ErrorException("活动已经下架！");
			}

				if ($prize_box = LotteryPrize::find()->where(['lottery_id' => $this->lottery_id])->limit(8)->orderBy('id asc')->all()) {
					$prize_relust_count = LotteryResult::find()->where(['lottery_id' => $this->lottery_id])->count();

			        $list = [];
					foreach ($prize_box as $key => $val) {
						$list[$val->id] = $key;
					}

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
					$lottery_prize = LotteryPrize::findOne(['id' => $rid]);
					$history = new LotteryResult();
					$history->customer_id = \Yii::$app->user->getId();
					$history->lottery_id = $lottery_prize->lottery_id;
					$history->lottery_prize_id=$lottery_prize->id;
					$history->creat_at = time();
					$history->save();
					$customer_coupon_id=0;
					if($lottery_prize->coupon){
						$customer_coupon = new CustomerCoupon();
						$customer_coupon->customer_id = \Yii::$app->user->getId();
						$customer_coupon->coupon_id = $lottery_prize->coupon->coupon_id;
						$customer_coupon->description = $model->title;
						if ($lottery_prize->coupon->date_type == 'DAYS') {
							$customer_coupon->start_time = date('Y-m-d H:i:s', time());
							$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $lottery_prize->coupon->expire_seconds);
						} else {
							$customer_coupon->start_time = $lottery_prize->coupon->date_start;
							$customer_coupon->end_time = $lottery_prize->coupon->date_end;
						}
						$customer_coupon->is_use = 0;
						$customer_coupon->date_added = date('Y-m-d H:i:s', time());
						$customer_coupon->save();
						$customer_coupon_id=$customer_coupon->customer_coupon_id;
					}
//					$coupon_array=['ECP170613004','ECP170613007','ECP170606010','ECP170613005','ECP170606012','ECP170613003','ECP170613002'];
//					if($coupon_array_model=Coupon::find()->where(['code'=>$coupon_array])->all()){
//						foreach ($coupon_array_model as $coupon) {
//							$customer_coupon_model = new CustomerCoupon();
//							$customer_coupon_model->customer_id = \Yii::$app->user->getId();
//							$customer_coupon_model->coupon_id = $coupon->coupon_id;
//							$customer_coupon_model->description = "618购物券";
//							if ($coupon->date_type == 'DAYS') {
//								$customer_coupon_model->start_time = date('Y-m-d H:i:s', time());
//								$customer_coupon_model->end_time = date('Y-m-d 23:59:59', time() + $coupon->expire_seconds);
//							} else {
//								$customer_coupon_model->start_time = $coupon->date_start;
//								$customer_coupon_model->end_time = $coupon->date_end;
//							}
//							$customer_coupon_model->is_use = 0;
//							$customer_coupon_model->date_added = date('Y-m-d H:i:s', time());
//							$customer_coupon_model->save();
//						}
//					}
					$data = ['status' => 1, 'prize' => $list[$rid], 'title' => $lottery_prize->title,'description'=>$lottery_prize->description,'customer_coupon_id'=>$customer_coupon_id];
				} else {
					throw  new ErrorException("对不起，抽奖活动已经结束！");
				}
		} catch (ErrorException $e) {
			if ($e->getCode() == 101) {
				$data = ['status' => 0, 'message' => $e->getMessage(), 'redirect' => Url::to(['/site/login', 'redirect' => '/game/index'], true)];
			} else {
				$data = ['status' => 0, 'message' => $e->getMessage()];
			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	public function actionApply()
	{
		try {
			if (\Yii::$app->user->isGuest) {
				throw new ErrorException('你还没有登陆，请先登陆！', 101);
			}
			if ($chance = PrizeChance::find()->where(['and', 'customer_id=' . \Yii::$app->user->getId(), 'status=0', "expiration_time>'" . date('Y-m-d H:i:s', time()) . "'"])->one()) {
				if ($prize_box = PrizeBox::find()->where(['status' => 1, 'type' => 'draw'])->limit(8)->orderBy('id asc')->all()) {
					$chance->status = 1;
					$chance->save();
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
					$model = PrizeBox::findOne(['id' => $rid]);
					$history = new PrizeBoxHistory();
					$history->customer_id = \Yii::$app->user->getId();
					$history->prize_box_id = $rid;
					$history->date_added = date('Y-m-d H;i:s', time());
					$history->save();
					$customer_coupon = new CustomerCoupon();
					$customer_coupon->customer_id = \Yii::$app->user->getId();
					$customer_coupon->coupon_id = $model->coupon->coupon_id;
					$customer_coupon->description = "购物抽奖";
					if ($model->coupon->date_type == 'DAYS') {
						$customer_coupon->start_time = date('Y-m-d 00:00:00', strtotime('+2 day'));
						$customer_coupon->end_time = date('Y-m-d 23:59:59', strtotime('+2 day') + $model->coupon->expire_seconds);
					} else {
						$customer_coupon->start_time = $model->coupon->date_start;
						$customer_coupon->end_time = $model->coupon->date_end;
					}
					$customer_coupon->is_use = 0;
					$customer_coupon->date_added = date('Y-m-d H:i:s', time());
					$customer_coupon->save();
					$time_msg="（使用时间：".date('m/d',strtotime($customer_coupon->start_time))."---".date('m/d',strtotime($customer_coupon->end_time))."有效）";
					$data = ['status' => 1, 'prize' => $list[$rid], 'customer_coupon_id' => $customer_coupon->customer_coupon_id, 'name' => $model->coupon->name, 'description' => $model->coupon->description.$time_msg];
				} else {
					throw  new ErrorException("对不起，抽奖活动已经结束！");
				}
			} else {
				throw new ErrorException("你的抽奖机会已用完，继续购物赢取抽奖机会！");
			}
		} catch (ErrorException $e) {
			if ($e->getCode() == 101) {
				$data = ['status' => 0, 'message' => $e->getMessage(), 'redirect' => Url::to(['/site/login', 'redirect' => '/game/index'], true)];
			} else {
				$data = ['status' => 0, 'message' => $e->getMessage()];
			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}

	public function actionSendNotice($customer_coupon_id)
	{
		if (!\Yii::$app->user->isGuest) {
			if ($open_id = \Yii::$app->user->identity->getWxOpenId()) {
				if ($model = CustomerCoupon::findOne(['customer_coupon_id' => $customer_coupon_id])) {
					if($model->coupon->model=="BUY_GIFTS"){
						$total="赠品券";
					}else{
						$total=$model->coupon->getRealDiscount() . ($model->coupon->type == 'F' ? "元" : "折");
					}
					$notice = new WxNotice();
					$notice->coupon($open_id, "https://m.mrhuigou.com/user-coupon/index",
						[
							'title' => '亲，您的手气真不错，获得了' . $model->coupon->name.",有效期内再次购物自动使用。",
							'total' =>$total,
							'exp_date' => $model->end_time,
						]
					);
				}
			}
		}
	}

	public function actionToday()
	{
		return $this->render('today');
	}

	public function actionAjaxOpen()
	{
		try {
			if (\Yii::$app->user->isGuest) {
				throw new ErrorException('你还没有登陆，请先登陆！', 101);
			}
			if ($model = Exercise::find()->where(['and', "unix_timestamp(begin_time)<=" . time(), 'unix_timestamp(end_time)>=' . time(), 'status=1'])->one()) {
				if ($userlog = ExerciseHistory::find()->where(['and', 'create_at>=' . strtotime(date('Y-m-d 00:00:00', time())), 'create_at<=' . strtotime(date('Y-m-d 23:59:59', time()))])->andWhere(['customer_id' => \Yii::$app->user->getId(), 'exercise_id' => $model->id])->one()) {
					throw new ErrorException('您今天已经拆过了，明天在来吧！');
				}
				$cur_rule = [];
				if ($rules = ExerciseRule::find()->where(['exercise_id' => $model->id, 'status' => 1])->andWhere(['and', 'unix_timestamp(start_time)<=' . time(), 'unix_timestamp(end_time)>=' . time()])->all()) {
					foreach ($rules as $rule) {
						if ($rule->is_subcription && !\Yii::$app->user->identity->getSubcription()) {
							continue;
						}
						$days = $rule->order_days ? $rule->order_days : 0;
						if ($rule->product_datas) {
							$model = Order::find()->joinWith('order_product')->where(['jr_order.customer_id' => \Yii::$app->user->getId(), 'jr_order.sent_to_erp' =>'Y'])->andWhere("jr_order.date_added>='" . date('Y-m-d', strtotime("-" . $days . " day"))."'")->andWhere(['jr_order_product.product_code' => explode("\r\n", trim($rule->product_datas))]);
						} else {
							$model = Order::find()->where(['customer_id' => \Yii::$app->user->getId(), 'sent_to_erp' =>'Y'])->andWhere("date_added>='" . date('Y-m-d', strtotime("-" . $days . " day"))."'");
						}

						if ($rule->order_count && $model->groupBy('jr_order.order_id')->count("*") < $rule->order_count) {
							continue;
						}
						if ($rule->order_total && $model->groupBy('jr_order.order_id')->sum('jr_order.total') < $rule->order_total) {
							continue;
						}

						$cur_rule = $rule;
						break;
					}
				} else {
					throw new ErrorException('当前活动还未开始1111');
				}
				if(!$cur_rule){
					throw new ErrorException('当前活动还未开始');
				}
			//	shuffle($cur_rule->coupon); //打乱数组顺序
				$arr = [];
				foreach ($cur_rule->coupon as $key => $val) {
					$arr[$val->id] = $val->probability;
				}

				$rid = $this->get_rand($arr); //根据概率获取奖项id


				$model=ExerciseRuleCoupon::findOne($rid);
				$view=$this->renderAjax('today-result',['model'=>$model]);
				$data=['status'=>1,'content'=>$view];
			} else {
				throw new ErrorException('活动已经结束！333');
			}
		} catch (ErrorException $e) {
			if ($e->getCode() == 101) {
				$data = ['status' => 0, 'message' => $e->getMessage(), 'redirect' => Url::to(['/site/login', 'redirect' => '/game/today'], true)];
			} else {
				$data = ['status' => 0, 'message' => $e->getMessage()];
			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
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
}