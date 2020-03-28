<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/12
 * Time: 19:09
 */
namespace fx\controllers;
use api\models\V1\AdvertiseDetail;
use api\models\V1\Coupon;
use api\models\V1\CustomerAuthentication;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerHongbao;
use api\models\V1\CustomerHongbaoHistroy;
use api\models\V1\CustomerTransaction;
use api\models\V1\ExerciseRuleCoupon;
use api\models\V1\Order;
use api\models\V1\PrizeBox;
use api\models\V1\VerifyCode;
use common\component\Helper\OrderSn;
use common\component\Notice\WxNotice;
use common\component\Wx\WxSdk;
use common\models\User;
use Yii;
use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class ShareController extends \yii\web\Controller {
	public function actionIndex()
	{
		if ($share_user_id = Yii::$app->request->get('share_user_id')) {
			Yii::$app->session->set('customer_share_user', $share_user_id);
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		if ($redirect = Yii::$app->request->get('redirect')) {
			return $this->redirect($redirect);
		}
        $coupons = [];
		$prize_box = PrizeBox::find()->where(['type'=>'register','status'=>1])->all();
		if($prize_box){
		    foreach ($prize_box as $value){
                $coupons[] = $value->coupon;
            }
        }
		//$coupons = Coupon::find()->where(['code' => ['ECP170418001']])->orderBy('discount')->all();
		$advertise = new AdvertiseDetail();
//		$focus_position = ['H5-FX-DES1'];
//		$ad_data = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-NEWS-DES1'];
        $ad_data = $advertise->getAdvertiserDetailByPositionCode($focus_position);
//print_r($ad_data);exit;
		return $this->render('index', ['ad_data' => $ad_data, 'coupons' => $coupons]);
	}

	public function actionGift()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		if (!$hongbao_id = Yii::$app->request->get('hongbao_id')) {
			throw  new NotFoundHttpException("没有找到相关页面");
		}
		if($hongbao=CustomerHongbao::findOne(['id' => $hongbao_id])){
			$history=CustomerHongbaoHistroy::find()->where(['customer_hongbao_id'=>$hongbao->id])->count();
			if($history<11){
					if(Yii::$app->user->getId()!==$hongbao->customer_id){
						if(Yii::$app->user->identity->telephone_validate){
							$this->open($hongbao_id);
						}
					}
			}
		}

		if ($hongbao = CustomerHongbao::findOne(['id' => $hongbao_id])) {
			if(Yii::$app->user->getId()!==$hongbao->customer_id){
				$my_coupons = CustomerCoupon::find()->where(['from_hongbao_id' => $hongbao->id, 'customer_id' => Yii::$app->user->getId()])->all();
				return $this->render('gift', ['hongbao' => $hongbao, 'my_coupons' => $my_coupons]);
			}else{
				$my_coupons = $model = ExerciseRuleCoupon::find()->where(['share_status'=>0])->all();
				return $this->render('my_gift', ['hongbao' => $hongbao, 'my_coupons' => $my_coupons]);
			}
		} else {
			throw  new NotFoundHttpException("没有找到相关页面");
		}

	}

	public function actionAjaxCheck()
	{
		try {
			if (\Yii::$app->request->getIsPost() && \Yii::$app->request->isAjax) {
				if (!$telephone = Yii::$app->request->post('telephone')) {
					throw new ErrorException('请输入手机号');
				}
				if (!$code = Yii::$app->request->post('check_code')) {
					throw new ErrorException('请输入手机验证码');
				}
				if (!$check = VerifyCode::findOne(['phone' => $telephone, 'code' => $code, 'status' => 0])) {
					throw new ErrorException('手机验证码不正确');
				} else {
					$check->status = 1;
					$check->save();
				}
				if ($model = User::findByUsername($telephone)) {
					if ($customer_auth = CustomerAuthentication::findOne(['customer_id' => Yii::$app->user->getId(), 'provider' => 'weixin'])) {
						$customer_auth->customer_id = $model->getId();
						$customer_auth->save();
					}
					$model->telephone_validate = 1;
					$model->save();
					Yii::$app->user->login($model, 3600 * 24 * 7); //登陆
				} else {
					if ($user = User::findIdentity(Yii::$app->user->getId())) {
						$user->telephone = $telephone;
						$user->telephone_validate = 1;
						$user->save();
						Yii::$app->user->login($user, 3600 * 24 * 7); //登陆
					}
				}
				$data = ['status' => 1, 'message' => '验证成功'];
			} else {
				throw new ErrorException('数据加载失败');
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}

	public function actionSubscription()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		if(Yii::$app->user->identity->getSubcription()){
			return $this->redirect('/coupon/index');
		}else{
			if(Order::find()->where(['customer_id'=>Yii::$app->user->getId(),'sent_to_erp'=>'Y'])->count()){
				return $this->redirect('/coupon/index');
			}
		}
		$coupons = PrizeBox::find()->where(['type' => 'register', 'status' => 1])->all();
		return $this->render('subscription',['coupons'=>$coupons]);
	}

	public function actionNew()
	{
		if ($share_user_id = Yii::$app->request->get('share_user_id')) {
			Yii::$app->session->set('customer_share_user', $share_user_id);
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		if (!Yii::$app->user->identity->getSubcription()) {
			return $this->redirect(['/share/subscription', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		$coupons = Coupon::find()->where(['code' => ['ECP170418001']])->orderBy('discount')->all();
		return $this->render('new', ['coupons' => $coupons]);
	}

	protected function open($hongbao_id)
	{   $message=[];
		if ($hongbao = CustomerHongbao::findOne(['id' => $hongbao_id])) {
			if (!$history = CustomerHongbaoHistroy::findOne(['customer_hongbao_id' => $hongbao->id, 'customer_id' => Yii::$app->user->getId()])) {
				$gift_coupons = [];
				if ($order_count = Order::find()->where(['customer_id' => Yii::$app->user->getId(), 'sent_to_erp' => 'Y'])->count()) {
					if ($model = ExerciseRuleCoupon::find()->all()) {
						foreach ($model as $value) {
							$gift_coupons[] = $value->coupon_id;
						}
					}
				} else {
					if ($gifts = PrizeBox::find()->where(['type' => 'register', 'status' => 1])->all()) {
						foreach ($gifts as $value) {
							if ($model = CustomerCoupon::find()->where(['customer_id' => Yii::$app->user->getId(), 'coupon_id' => $value->coupon_id, 'is_use' => [0, 1]])->andWhere("end_time>'" . date('Y-m-d H:i:s', time()) . "''")) {
								continue;
							} else {
								$gift_coupons[] = $value->coupon_id;
							}
						}
					}
				}
				if ($gift_coupons) {
					if ($coupons = Coupon::find()->where(['coupon_id' => $gift_coupons])->all()) {
						$total = 0;
						foreach ($coupons as $coupon) {
							$customer_coupon = new CustomerCoupon();
							$customer_coupon->from_hongbao_id = $hongbao->id;
							$customer_coupon->customer_id = Yii::$app->user->getId();
							$customer_coupon->coupon_id = $coupon->coupon_id;
							$customer_coupon->description = "帮好友拆包所得";
							$customer_coupon->is_use = 0;
							if ($coupon->date_type == 'DAYS') {
								$customer_coupon->start_time = date('Y-m-d H:i:s', time());
								$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $coupon->expire_seconds);
							} else {
								$customer_coupon->start_time = $coupon->date_start;
								$customer_coupon->end_time = $coupon->date_end;
							}
							$customer_coupon->date_added = date('Y-m-d H:i:s', time());
							$customer_coupon->save();
							if ($coupon->type == 'F') {
								$total += $coupon->discount;
							} else {
								if ($coupon->max_discount) {
									$total += $coupon->max_discount;
								}
							}
						}
						$history = new CustomerHongbaoHistroy();
						$history->customer_hongbao_id = $hongbao->id;
						$history->customer_id = Yii::$app->user->getId();
						$history->create_at = time();
						$history->amount = $total;
						$history->save();
						$message[]=[
							'customer_id'=>Yii::$app->user->getId(),
							'url'=>Url::to(['/share/gift','hongbao_id'=>$hongbao->id],true),
							'content'=>['title'=>'亲，感谢你为好友助力，恭喜您获得'.$history->amount."元优惠券奖励。",'name'=>'助力好友拆包','content'=>'相应优惠券已经存入你的个人帐户。'],
						];
					}
				}
			}
			//统计分享用户数进行返赠分享人的操作
			if($share_count=CustomerHongbaoHistroy::find()->where(['customer_hongbao_id'=>$hongbao_id])->count()){
				if($share_count==2){
					if ($rule_coupons = ExerciseRuleCoupon::find()->where(['share_status'=>0])->all()) {
						$total = 0;
						foreach ($rule_coupons as $coupon) {
							$customer_coupon = new CustomerCoupon();
							$customer_coupon->from_hongbao_id = $hongbao->id;
							$customer_coupon->customer_id = $hongbao->customer_id;
							$customer_coupon->coupon_id = $coupon->coupon->coupon_id;
							$customer_coupon->description = "朋友助力获得";
							$customer_coupon->is_use = 0;
							if ($coupon->coupon->date_type == 'DAYS') {
								$customer_coupon->start_time = date('Y-m-d H:i:s', time());
								$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $coupon->coupon->expire_seconds);
							} else {
								$customer_coupon->start_time = $coupon->coupon->date_start;
								$customer_coupon->end_time = $coupon->coupon->date_end;
							}
							$customer_coupon->date_added = date('Y-m-d H:i:s', time());
							$customer_coupon->save();
							if ($coupon->coupon->type == 'F') {
								$total += $coupon->coupon->discount;
							} else {
								if ($coupon->coupon->max_discount) {
									$total += $coupon->coupon->max_discount;
								}
							}
						}
						if($hongbao->amount) {
							$trascation = new CustomerTransaction();
							$trascation->customer_id = $hongbao->customer_id;
							$trascation->amount = $hongbao->amount;
							$trascation->trade_no = OrderSn::generateNumber();
							$trascation->description = "助力返现";
							$trascation->date_added = date('Y-m-d H:i:s', time());
							$trascation->save();
						}
						$history = new CustomerHongbaoHistroy();
						$history->customer_hongbao_id = $hongbao->id;
						$history->customer_id = $hongbao->customer_id;
						$history->create_at = time();
						$history->amount = $total+$hongbao->amount;
						$history->save();
						$message[]=[
							'customer_id'=>$hongbao->customer_id,
							'url'=>Url::to(['/share/gift','hongbao_id'=>$hongbao->id],true),
							'content'=>['title'=>'亲，您的购物礼包已成功打开，恭喜您获得'.$history->amount."元优惠券奖励！",'name'=>'邀请好友拆包','content'=>'相应奖品已经存入你的个人帐户。'],
						];
					}
				}
			}
		}
		$this->sendMessage($message);
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

	public function actionSign($url)
	{
		$data=Yii::$app->wechat->jsApiConfigUrl($url);
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
}