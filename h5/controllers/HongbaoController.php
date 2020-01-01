<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/11
 * Time: 15:40
 */
namespace h5\controllers;
use api\models\V1\Coupon;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerHongbao;
use api\models\V1\CustomerHongbaoHistroy;
use api\models\V1\CustomerTransaction;
use common\component\Helper\OrderSn;
use common\component\Hongbao\Money;
use common\component\Notice\WxNotice;
use common\models\User;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;
use api\models\V1\AdvertiseDetail;
class HongbaoController extends \yii\web\Controller {
	public function actionIndex()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		if($hongbao_id = Yii::$app->request->get('id')){
			$model = CustomerHongbao::findOne($hongbao_id);
		}else{
			$model = CustomerHongbao::find()->where(['customer_id'=>Yii::$app->user->getId(),'status'=>0])->orderBy('id desc')->one();
		}
		if ($model) {
			if(!$model->split_amount){
				$data=new Money($model->amount,5);
				$split=$data->split();
				$model->split_amount=Json::encode($split);
				$model->save();
			}
			if($model->status || count($model->history)>=5){
				return $this->redirect(['/hongbao/result','id'=>$model->id]);
			}else{
				if(CustomerHongbaoHistroy::findOne(['customer_hongbao_id'=>$model->id,'customer_id'=>Yii::$app->user->getId()])){
					return $this->redirect(['/hongbao/result','id'=>$model->id]);
				}else{
					return $this->render('index', ['model' => $model]);
				}
			}
		} else {
			return $this->redirect('/user-hongbao/index');
		}
	}

	public function actionResult(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		$hongbao_id = Yii::$app->request->get('id');
		if ($model = CustomerHongbao::findOne($hongbao_id)) {
			if($model->split_amount){
				$money_arryay=Json::decode($model->split_amount);
			}else{
				$money_arryay=['0'];
			}
			$pos = array_search(max($money_arryay), $money_arryay);
			$history=CustomerHongbaoHistroy::find()->where(['customer_hongbao_id'=>$hongbao_id])->all();
			$cur=CustomerHongbaoHistroy::findOne(['customer_hongbao_id'=>$hongbao_id,'customer_id'=>Yii::$app->user->getId()]);
			$sub_total=CustomerHongbaoHistroy::find()->where(['customer_hongbao_id'=>$hongbao_id])->sum('amount');
			return $this->render('result', ['model' => $model,'history'=>$history,'cur'=>$cur,'pos'=>$pos,'sub_total'=>$sub_total?$sub_total:0]);
		} else {
			throw new NotFoundHttpException("没有找到相关信息");
		}
	}
	public function actionBlock(){
		throw  new NotFoundHttpException("活动结束");
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => Yii::$app->request->getAbsoluteUrl()]);
		}
		$prize=[
			'fresh'=>'ECP170228007',
			'wine'=>'ECP170228006',
			'snacks'=>'ECP170228005',
			'grain'=>'ECP170228003',
			'washing'=>'ECP170228004'
		];
		if(!isset($prize[Yii::$app->request->get('code')])){
			throw  new NotFoundHttpException("没有找到相关页面");
		}
		$coupon=Coupon::findOne(['code'=>$prize[Yii::$app->request->get('code')]]);
		if($coupon){
			$status=false;
			if(!$model=CustomerCoupon::findOne(['customer_id'=>Yii::$app->user->getId(),'coupon_id'=>$coupon->coupon_id])){
				$model = new CustomerCoupon();
				$model->customer_id = \Yii::$app->user->getId();
				$model->coupon_id = $coupon->coupon_id;
				$model->description = "购物领红包";
				if ($coupon->date_type == 'DAYS') {
					$model->start_time = date('Y-m-d H:i:s', time());
					$model->end_time = date('Y-m-d 23:59:59', time() + $coupon->expire_seconds);
				} else {
					$model->start_time = $coupon->date_start;
					$model->end_time = $coupon->date_end;
				}
				$model->is_use = 0;
				$model->date_added = date('Y-m-d H:i:s', time());
				$model->save();
				$message[]=[
					'customer_id'=>\Yii::$app->user->getId(),
					'url'=>Url::to(['/user-coupon/index'],true),
					'content'=>['title'=>'亲，恭喜您获得了'.$coupon->name,'name'=>"购物领红包",'content'=>$coupon->name."，已经存入你的账户。"]
				];
				$this->sendMessage($message);
			}else{
				$status=true;
			}
			$advertise = new AdvertiseDetail();
			$focus_position = ['H5-2L-HBAO'];
			$ad = $advertise->getAdvertiserDetailByPositionCode($focus_position);
			return $this->render('block',['model'=>$model,'status'=>$status,'ad'=>$ad]);
		}else{
			throw  new NotFoundHttpException("没有找到相关页面");
		}
	}
	public function actionOpen()
	{
		try {
			$message=[];
			if (\Yii::$app->request->getIsPost() && \Yii::$app->request->isAjax) {
				if ($model = CustomerHongbao::findOne(['id' => Yii::$app->request->post('id'), 'status' => 0])) {
					$index=CustomerHongbaoHistroy::find()->where(['customer_hongbao_id'=>$model->id])->count();
					if($index>5){
						throw new ErrorException('红包已被领完了！');
					}
					$index=$index?$index:0;
					$split_amount=$model->split_amount?Json::decode($model->split_amount):[];
					if(!isset($split_amount[$index])){
						throw new ErrorException('红包已被领完了！');
					}
					if(!$history=CustomerHongbaoHistroy::findOne(['customer_hongbao_id'=>$model->id,'customer_id'=>Yii::$app->user->getId()])){
						if($index==4){
							$model->status=1;
							$model->update_at=time();
							$model->save();
						}
						$history = new CustomerHongbaoHistroy();
						$history->customer_hongbao_id = $model->id;
						$history->customer_id = Yii::$app->user->getId();
						$history->amount=$split_amount[$index];
						$history->create_at = time();
						$history->save();
						//存入余额
						$banlance = new CustomerTransaction();
						$banlance->customer_id = $history->customer_id;
						$banlance->trade_no = OrderSn::generateNumber();
						$banlance->amount = $history->amount;
						$banlance->description = "拆红包";
						$banlance->date_added = date('Y-m-d H:i:s', time());
						$banlance->save();
						$message[]=[
							'customer_id'=>$banlance->customer_id,
							'url'=>Url::to(['/hongbao/result','id'=>$model->id],true),
							'content'=>['title'=>'亲，恭喜您获得了'.$banlance->amount."元现金红包",'name'=>"拆红包",'content'=>$banlance->amount."元现金，已经存入你的账户余额中。"]
						];
						if(Yii::$app->user->getId()!==$model->customer_id){
							$banlance = new CustomerTransaction();
							$banlance->customer_id = $model->customer_id;
							$banlance->trade_no = OrderSn::generateNumber();
							$banlance->amount = $history->amount;
							$banlance->description = "好友拆红包返现";
							$banlance->date_added = date('Y-m-d H:i:s', time());
							$banlance->save();
							$message[]=[
								'customer_id'=>$banlance->customer_id,
								'url'=>Url::to(['/hongbao/result','id'=>$model->id],true),
//								'content'=>['title'=>'亲，您的好友为你助力，恭喜您获得了'.$banlance->amount."元现金红包",'name'=>"好友帮我拆红包",'content'=>$banlance->amount."元现金，已经存入你的账户余额中。"]
                                'content'=>['title'=>'亲，您的好友为你助力，恭喜您获得了'.$banlance->amount."元现金红包",'name'=>$banlance->amount."元现金红包",'content'=>$banlance->amount."元现金，已经存入你的账户余额中。"]
							];
						}
						$this->sendMessage($message);
						$data = ['status' => 1, 'redirect' => Url::to(['/hongbao/result','id'=>$model->id],true)];
					}else{
						throw new ErrorException('您已经领过了！');
					}
				}else{
					throw new ErrorException('红包已被领完了！');
				}
			} else {
				throw new ErrorException('数据加载失败');
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	protected function sendMessage($message=array()){
		if($message){
			foreach($message as $value){
				if($user=User::findIdentity($value['customer_id'])){
					if($open_id=$user->getWxOpenId()){
						$notice=new WxNotice();
						$notice->zhongjiang($open_id,$value['url'],$value['content']);
					}
				}
			}
		}
	}

}