<?php
namespace fx\controllers;
use api\models\V1\AdvertiseDetail;
use api\models\V1\Affiliate;
use api\models\V1\AffiliatePersonal;
use api\models\V1\AffiliateTransactionFlow;
use api\models\V1\CustomerAffiliate;
use api\models\V1\CustomerCommission;
use api\models\V1\CustomerCommissionDraw;
use api\models\V1\CustomerCommissionFlow;
use api\models\V1\CustomerFollower;
use api\models\V1\Order;
use common\component\Helper\OrderSn;
use fx\models\AffiliateForm;
use fx\models\AffiliateTransactionForm;
use fx\models\CustomerAffiliateForm;
use fx\models\CustomerCommissionForm;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use Yii;
use yii\helpers\Url;

class UserShareController extends \yii\web\Controller {
	public function actions(){
		return [
			'wx-js-call' => [
				'class' => 'common\component\Payment\WxPay\WxpayAction',
			],
		];

	}
	public function actionHelp(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}

        if (!$affiliate = Affiliate::findOne(['customer_id' => Yii::$app->user->getId()])) {
            return $this->redirect('/user-share/apply');
        } else {
            if (!$affiliate->status) {
                return $this->redirect('/user-share/result');
            }
        }

		$data = [];
		$advertise = new AdvertiseDetail();
		$focus_position = ['H5-1LFX-AD'];
		$data['ad_1'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
//		$focus_position = ['H5-1LFX-DES1'];
//		$data['ad_2'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
//		$focus_position = ['H5-1LFX-DES2'];
//		$data['ad_3'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

        if($model=Affiliate::findOne(['customer_id'=>\Yii::$app->user->getId()])) {
            $data['model'] = $model;
        }
		return $this->render('help',$data);

	}
	public function actionIndex()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
		if (!$affiliate = Affiliate::findOne(['customer_id' => Yii::$app->user->getId()])) {
			return $this->redirect('/user-share/apply');
		} else {
			if (!$affiliate->status) {
				return $this->redirect('/user-share/result');
			}
		}
		$model = AffiliateTransactionFlow::find()->where(['affiliate_id' => Yii::$app->user->identity->getAffiliateId(),'status'=>1])->andWhere(['>','amount',0]);
		$history_total = $model->sum('amount');
		$month_total = $model->andWhere(['=','MONTH(FROM_UNIXTIME(create_at))', date('m')])->andWhere(['=','year(FROM_UNIXTIME(create_at))', date('Y')])->sum('amount');
		$week_total = $model->andWhere(['=', 'WEEK(FROM_UNIXTIME(create_at),1)', date('W')])->andWhere(['=','MONTH(FROM_UNIXTIME(create_at))', date('m')])->andWhere(['=','year(FROM_UNIXTIME(create_at))', date('Y')])->sum('amount');
		$today_total = $model->andWhere(['>=', 'FROM_UNIXTIME(create_at)', date('Y-m-d')])->sum('amount');
		return $this->render('index',[
			'today_total'=>$today_total ? floatval($today_total) : 0,
			'week_total'=>$week_total ? floatval($week_total) : 0,
			'month_total'=>$month_total ? floatval($month_total) : 0,
			'history_total'=>$history_total ? floatval($history_total) : 0
			]);
	}

	public function actionApply()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
		$redirect = Yii::$app->request->get('redirect');
		$advertise = new AdvertiseDetail();
		$focus_position = ['H5-1LFX-AD'];
		$banner = $advertise->getAdvertiserDetailByPositionCode($focus_position);
//		$model = new CustomerAffiliateForm();
		$model = new AffiliateForm();
		if ($model->load(Yii::$app->request->post()) && $model->submit()) {
		    if($redirect){
                return $this->redirect($redirect);
            }
			return $this->redirect('/user-share/help');
		} else {
			return $this->render('apply', [
				'model' => $model,
				'banner'=>$banner
			]);
		}
	}
	public function actionXieyi(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
		return $this->render('xieyi');
	}
	public function actionResult()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
        if (!$affiliate = Affiliate::findOne(['customer_id' => Yii::$app->user->getId()])) {
            return $this->redirect('/user-share/apply');
        } else {
            if (!$affiliate->status) {
                return $this->render('result');
            }
        }
		return $this->redirect('/user-share/index');
	}
	public function actionList(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
		$type=Yii::$app->request->get('type','page');
		$data = [];
		$advertise = new AdvertiseDetail();
		if($type=='product'){
			$focus_position = ['H5-1LFX-DES1'];
			$data['ad_data'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		}else{
			$focus_position = ['H5-1LFX-DES2'];
			$data['ad_data'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		}
		return $this->render('list',$data);
	}
	public function actionFollower()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}

		$model = CustomerFollower::find()->where(['customer_id' => Yii::$app->user->getId()]);
		$history_total = $model->count('follower_id');
		$month_total = $model->andWhere(['=','MONTH(FROM_UNIXTIME(creat_at))', date('m')])->andWhere(['=','year(FROM_UNIXTIME(creat_at))', date('Y')])->count('follower_id');
		$week_total = $model->andWhere(['=', 'WEEK(FROM_UNIXTIME(creat_at))', date('W')])->andWhere(['=','MONTH(FROM_UNIXTIME(creat_at))', date('m')])->andWhere(['=','year(FROM_UNIXTIME(creat_at))', date('Y')])->count('follower_id');
		$today_total = $model->andWhere(['>=', 'FROM_UNIXTIME(creat_at)', date('Y-m-d')])->count('follower_id');
		$model=CustomerFollower::find()->where(['customer_id'=>Yii::$app->user->getId()]);
		$dataProvider = new ActiveDataProvider([
			'query' => $model->orderby('id desc'),
			'pagination' => [
				'pagesize' => '10',
			]
		]);
		return $this->render('follower', ['model' => $model, 'dataProvider' => $dataProvider,
			'today_total'=>$today_total ? floatval($today_total) : 0,
			'week_total'=>$week_total ? floatval($week_total) : 0,
			'month_total'=>$month_total ? floatval($month_total) : 0,
			'history_total'=>$history_total ? floatval($history_total) : 0
		]);
	}

	public function actionOrder()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
		$model = Order::find()->where(['affiliate_id' => Yii::$app->user->identity->getAffiliateId(), 'order_type_code' => ['Affiliate']])->andWhere(['>','total',0])->andWhere(['not in','order_status_id',[6,7]]);
		$dataProvider = new ActiveDataProvider([
			'query' => $model->orderby('order_id desc'),
			'pagination' => [
				'pagesize' => '10',
			]
		]);
		return $this->render('order', ['model' => $model, 'dataProvider' => $dataProvider,'title'=>'订单收益','type'=>'aff_customer']);
	}
    public function actionOrderAffPerson()
    {

        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
        $model = Order::find()->where(['current_source_customer_id' => \Yii::$app->user->getId(), 'order_type_code' => ['normal', 'presell']])->andWhere(['>','total',0])->andWhere(['not in','order_status_id',[6,7]]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderby('order_id desc'),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('order', ['model' => $model, 'dataProvider' => $dataProvider,'title'=>'分享商品收益','type'=>'aff_personal']);
    }

	public function actionApplyDraw(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}

		if(!$open_id=\Yii::$app->session->get('open_id')){
			return $this->redirect(['/user-share/wx-js-call','path'=>Url::to(['/user-share/apply-draw'],true)]);
		}
		$model=new AffiliateTransactionForm($open_id);
		if($model->load(Yii::$app->request->post()) && $model->save()){
			return $this->redirect('/user-share/apply-result');
		}
		return $this->render('apply-draw',['model'=>$model]);
	}
	public function actionApplyResult(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
		return $this->render('apply-result');
	}

	public function actionCommission()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
		}
        $model = AffiliateTransactionFlow::find()->where(['affiliate_id' => Yii::$app->user->identity->getAffiliateId()]);
		$dataProvider = new ActiveDataProvider([
			'query' => $model->orderby('id desc'),
			'pagination' => [
				'pagesize' => '10',
			]
		]);
		return $this->render('commission', ['model' => $model, 'dataProvider' => $dataProvider]);
	}
	public function actionAffiliatePersonal(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
	    $aff_customer = CustomerAffiliate::findOne(['customer_id'=>Yii::$app->user->getId()]);
	    if(!$aff_customer){
	        return $this->redirect(['user-share/apply','redirect'=>'/user-share/affiliate-personal']);//申请合伙人
        }
        $affiliate_personal_id = 1;
        $model = AffiliatePersonal::findOne(['affiliate_personal_id'=>$affiliate_personal_id]);
        if($model){
            $share_products = [];
            if($model->details){
                foreach ($model->details as $detail){
                    if($detail->product){
                        $share_products[] = $detail->product;
                    }
                }
            }
            return $this->render('aff-personal',['model'=>$model,'share_products'=>$share_products]);
        }


    }


}
