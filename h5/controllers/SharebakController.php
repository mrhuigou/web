<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/12
 * Time: 19:09
 */
namespace h5\controllers;
use api\models\V1\AdvertiseDetail;
use api\models\V1\Coupon;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerHongbao;
use api\models\V1\CustomerHongbaoHistroy;
use api\models\V1\CustomerTransaction;
use api\models\V1\Order;
use common\component\Helper\OrderSn;
use common\component\Notice\WxNotice;
use common\models\User;
use Yii;
use yii\helpers\Url;

class ShareBakController extends \yii\web\Controller {
    public function actionIndex()
    {
        if($share_user_id=Yii::$app->request->get('share_user_id')){
            Yii::$app->session->set('customer_share_user',$share_user_id);
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
        }
        if($redirect=Yii::$app->request->get('redirect')){
	        return $this->redirect($redirect);
        }
        $advertise = new AdvertiseDetail();
        $focus_position = ['H5-FX-DES1'];
        $ad_data= $advertise->getAdvertiserDetailByPositionCode($focus_position);
        return $this->render('index',['ad_data'=>$ad_data]);
    }
	public function actionHongbao(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		if ($hongbao = CustomerHongbao::findOne(['id'=>Yii::$app->request->get('hongbao_id')])) {
			$status=0;
			if($hongbao->customer_id==Yii::$app->user->getId()){
				$status=1;
			}
			if(!$status && count($hongbao->history)>5){
				$status=2;
			}
			if(!$status && ($history=CustomerHongbaoHistroy::find()->where(['customer_id'=>Yii::$app->user->getId()])->all())){
				foreach ($history as $value){
					if($hongbao->customer_id==$value->hongbao->customer_id){
						$status=3;
						break;
					}
				}
			}
			if($status==0){
				if($history=CustomerHongbaoHistroy::findOne(['customer_hongbao_id'=>$hongbao->id,'customer_id'=>Yii::$app->user->getId()])){
					$history = new CustomerHongbaoHistroy();
					$history->customer_hongbao_id = $hongbao->id;
					$history->customer_id = Yii::$app->user->getId();
					$history->create_at = time();
					$history->amount = 0;
					$history->save();
					//拆包用户返现金券
					$this->sendCoupon('ECP161025006',Yii::$app->user->getId());
				}
				if(count($hongbao->history)==0){
					if($history=CustomerHongbaoHistroy::findOne(['customer_hongbao_id'=>$hongbao->id,'customer_id'=>$hongbao->customer_id])){
						$history = new CustomerHongbaoHistroy();
						$history->customer_hongbao_id = $hongbao->id;
						$history->customer_id = $hongbao->customer_id;
						$history->create_at = time();
						$history->amount = 0;
						$history->save();
						//拆包用户返现金券
						$this->sendCoupon('ECP161025006',$hongbao->customer_id,'好友助力获得');
					}
				}
			}
		}
		return $this->render('hongbao',['hongbao'=>$hongbao,'status'=>$status]);
	}

	private function sendCoupon($code,$customer_id,$message="助力好友获得"){
		if ($coupon = Coupon::findOne(['code' => $code])) {
			$customer_coupon = new CustomerCoupon();
			$customer_coupon->customer_id = $customer_id;
			$customer_coupon->coupon_id = $coupon->coupon_id;
			$customer_coupon->description = $message;
			$customer_coupon->is_use = 0;
			if ($coupon->date_type == 'DAYS') {
				$customer_coupon->start_time = date('Y-m-d H:i:s', time());
				$customer_coupon->end_time = date('Y-m-d H:i:s', time() + $coupon->expire_seconds);
			} else {
				$customer_coupon->start_time = $coupon->date_start;
				$customer_coupon->end_time = $coupon->date_end;
			}
			$customer_coupon->date_added = date('Y-m-d H:i:s', time());
			$customer_coupon->save();
		}
	}




    public function actionGift()
    {
        if($share_user_id=Yii::$app->request->get('share_user_id')){
            Yii::$app->session->set('customer_share_user',$share_user_id);
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
        }
        if ($hongbao = CustomerHongbao::findOne(['id'=>Yii::$app->request->get('hongbao_id')])) {
            if(count($hongbao->history)>10){
                return $this->redirect(['/share/index']);
            }
        }
        $hongbao = $this->open();
        if($hongbao){
	        if( $customer_order_count=Order::find()->where(['customer_id' => Yii::$app->user->getId(), 'sent_to_erp' => 'Y'])->count("*")){
		        return $this->render('gift', ['hongbao' => $hongbao]);
	        }else{
		        return $this->redirect(['/share/subscription', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
	        }
        }else{
	        return $this->redirect(['/share/index']);
        }
    }

    public function actionSubscription()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
        }
        return $this->render('subscription');
    }

    public function actionNew()
    {
        if($share_user_id=Yii::$app->request->get('share_user_id')){
            Yii::$app->session->set('customer_share_user',$share_user_id);
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
        }
        if (!Yii::$app->user->identity->getSubcription()) {
            return $this->redirect(['/share/subscription', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
        }
        $coupons=Coupon::find()->where(['code' => ['ECP161025002', 'ECP161025003', 'ECP161025004', 'ECP161123002']])->all();
        return $this->render('new',['coupons'=>$coupons]);
    }

    protected function open()
    {
        $message = [];
        if ($hongbao = CustomerHongbao::findOne(['id'=>Yii::$app->request->get('hongbao_id')])) {
            if (($hongbao->customer_id!==Yii::$app->user->getId()) &&(!$history = CustomerHongbaoHistroy::findOne(['customer_hongbao_id' => $hongbao->id, 'customer_id' => Yii::$app->user->getId()]))) {
                $history = new CustomerHongbaoHistroy();
                $history->customer_hongbao_id = $hongbao->id;
                $history->customer_id = Yii::$app->user->getId();
                $history->create_at = time();
                $history->amount = 0;
                $history->save();
                if($customer_order_count=Order::find()->where(['customer_id' => Yii::$app->user->getId(), 'sent_to_erp' => 'Y'])->count("*")){
                    //拆包用户返现金券
                    if ($coupon = Coupon::findOne(['code' => 'ECP161025006'])) {
                        $customer_coupon = new CustomerCoupon();
                        $customer_coupon->customer_id = Yii::$app->user->getId();
                        $customer_coupon->coupon_id = $coupon->coupon_id;
                        $customer_coupon->description = "帮好友拆礼包获得";
                        $customer_coupon->is_use = 0;
                        if ($coupon->date_type == 'DAYS') {
                            $customer_coupon->start_time = date('Y-m-d H:i:s', time());
                            $customer_coupon->end_time = date('Y-m-d H:i:s', time() + $coupon->expire_seconds);
                        } else {
                            $customer_coupon->start_time = $coupon->date_start;
                            $customer_coupon->end_time = $coupon->date_end;
                        }
                        $customer_coupon->date_added = date('Y-m-d H:i:s', time());
                        $customer_coupon->save();
                        $message[] = [
                            'customer_id' => $customer_coupon->customer_id,
                            'url' => Url::to(['/user-coupon/index'], true),
                            'content' => ['title' => "亲，恭喜您获得了一张现金券！", 'name' => "拆红包通知", 'content' => "在帐户中心优惠券中查看！"]
                        ];
                    }
                }
                $share_count = CustomerHongbaoHistroy::find()->where(['customer_hongbao_id' => $hongbao->id])->count("*");

                if($share_count<=3){
                    if($share_count<3){
                        $url=Url::to(['/share/gift', 'share_user_id' => $hongbao->customer_id, 'hongbao_id' => $hongbao->id], true);
                        $content="还差" . (3 - $share_count) . "个人,继续分享！";
                    }else{
                        $url=Url::to(['/hongbao/result', 'id' => $hongbao->id], true);
                        $content="您的红包已经被成功拆开！点击查看！";
                    }
                    $message[] = [
                        'customer_id' => $hongbao->customer_id,
                        'url' => $url,
                        'content' => ['title' => "亲，您的好友[ " . Yii::$app->user->identity->nickname . " ]帮你拆了一个红包！", 'name' => "拆红包通知", 'content' => $content]
                    ];
                }
                $share_count = CustomerHongbaoHistroy::find()->where(['customer_hongbao_id' => $hongbao->id])->count("*");
                if ($share_count >= 3) {
                    if (!$share_self = CustomerHongbaoHistroy::findOne(['customer_hongbao_id' => $hongbao->id, 'customer_id' => $hongbao->customer_id])) {
                        $self_share_count = CustomerHongbaoHistroy::find()->where(['customer_id' => $hongbao->customer_id])->andWhere(['>','create_at',strtotime('2016-10-28')])->andWhere(['>','amount',0])->count();
                        if ($self_share_count == 0) {
                            $amount = $hongbao->amount + 5;
                        } elseif ($self_share_count == 1) {
                            $amount = $hongbao->amount + 2;
                        } elseif ($self_share_count == 2) {
                            $amount = $hongbao->amount + 3;
                        } else {
                            $amount = $hongbao->amount;
                        }
                        $share_self = new CustomerHongbaoHistroy();
                        $share_self->customer_hongbao_id = $hongbao->id;
                        $share_self->customer_id = $hongbao->customer_id;
                        $share_self->amount = $amount;
                        $share_self->create_at = time();
                        $share_self->save();
                        //存入余额
                        $banlance = new CustomerTransaction();
                        $banlance->customer_id = $share_self->customer_id;
                        $banlance->trade_no = OrderSn::generateNumber();
                        $banlance->amount = $share_self->amount;
                        $banlance->description = "红包转入";
                        $banlance->date_added = date('Y-m-d H:i:s', time());
                        $banlance->save();
                        if($hongbao_model=CustomerHongbao::findOne(['id'=>$hongbao->id])){
                            $hongbao->status=1;
                            $hongbao->update_at=time();
                            $hongbao->save();
                        }
                        $message[] = [
                            'customer_id' => $banlance->customer_id,
                            'url' => Url::to(['/hongbao/result', 'id' => $hongbao->id], true),
//                            'content' => ['title' => '亲，恭喜您获得了' . $banlance->amount . "元现金红包", 'name' => "拆红包通知", 'content' => "相应红包金额，已经存入你的账户余额中。"]
                            'content' => ['title' => '亲，恭喜您获得了' . $banlance->amount . "元现金红包", 'name' => $banlance->amount . "元现金红包", 'content' => "相应红包金额，已经存入你的账户余额中。"]
                        ];
                    }
                }
            }
        }
        $this->sendMessage($message);
        return $hongbao;
    }

    private function sendNewCustomerGift()
    {
        $customer_orders = Order::find()->where(['customer_id' => Yii::$app->user->getId(), 'sent_to_erp' => 'Y'])->count("*");
        if ($customer_orders==0){
            if (Yii::$app->user->identity->getSubcription()) {
                if ($customer_coupons = Coupon::find()->where(['code' => ['ECP161025001', 'ECP161025002', 'ECP161025003', 'ECP161025004', 'ECP161025005']])->all()) {
                    $ids = [];
                    foreach ($customer_coupons as $coupon) {
                        $ids[] = $coupon->coupon_id;
                    }
                    if (!$customer_model = CustomerCoupon::find()->where(['coupon_id' => $ids,'customer_id'=>Yii::$app->user->getId()])->all()) {
                        foreach ($customer_coupons as $coupon) {
                            $c_model = new CustomerCoupon();
                            $c_model->customer_id = Yii::$app->user->getId();
                            $c_model->coupon_id = $coupon->coupon_id;
                            $c_model->description = $coupon->name;
                            $c_model->is_use = 0;
                            if ($coupon->date_type == 'DAYS') {
                                $c_model->start_time = date('Y-m-d H:i:s', time());
                                $c_model->end_time = date('Y-m-d H:i:s', time() + $coupon->expire_seconds);
                            } else {
                                $c_model->start_time = $coupon->date_start;
                                $c_model->end_time = $coupon->date_end;
                            }
                            $c_model->date_added = date('Y-m-d H:i:s', time());
                            $c_model->save();
                        }
                    }
                }
            }
        }

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