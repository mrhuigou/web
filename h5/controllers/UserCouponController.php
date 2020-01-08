<?php
namespace h5\controllers;
use api\models\V1\Coupon;
use api\models\V1\CouponProduct;
use yii\data\ActiveDataProvider;
use h5\models\CouponCardForm;
use Yii;
use api\models\V1\CustomerCoupon;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class UserCouponController extends \yii\web\Controller {
	public function actionIndex()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$model = CustomerCoupon::find()->joinWith(['coupon' => function ($query) {
			$query->andFilterWhere([">=", "jr_coupon.status", 1])
                ->andFilterWhere(["!=", "jr_coupon.is_entity", 1])
            ;
		}])->where(['customer_id' => \Yii::$app->user->identity->getId(), 'is_use' => 0])
			->andWhere([">=", "end_time", date('Y-m-d H:i:s', time())]);
		$dataProvider = new ActiveDataProvider([
			'query' => $model->orderBy('jr_coupon.is_entity desc,end_time asc,discount desc,customer_coupon_id asc'),
			'pagination' => [
				'pagesize' => '4',
			]
		]);
		return $this->render('index', ['model' => $model, 'dataProvider' => $dataProvider]);
	}

    /**
     * @desc 查看提货券
     */
	public function actionIndexDelivery()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$model = CustomerCoupon::find()->joinWith(['coupon' => function ($query) {
			$query->andFilterWhere([">=", "jr_coupon.status", 1]);
		}])->where(['customer_id' => \Yii::$app->user->identity->getId(), 'is_use' => 0])
			->andWhere([">=", "end_time", date('Y-m-d H:i:s', time())])
            ->andFilterWhere(["=", "jr_coupon.is_entity", 1]);
		$dataProvider = new ActiveDataProvider([
			'query' => $model->orderBy('jr_coupon.is_entity desc,end_time asc,discount desc,customer_coupon_id asc'),
			'pagination' => [
				'pagesize' => '4',
			]
		]);

		return $this->render('index-delivery', ['model' => $model, 'dataProvider' => $dataProvider]);
	}

	public function actionCard()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		if (!Yii::$app->user->identity->telephone_validate) {
			return $this->redirect(['/user/security-set-telephone', 'redirect' => '/user-coupon/delivery-card']);
		}
		$model = new CouponCardForm();
		if ($model->load(Yii::$app->request->post()) && $customer_coupon=$model->save()) {
			$this->SendNotice($customer_coupon->customer_coupon_id);
			$coupon = Coupon::findOne(['coupon_id'=>$customer_coupon->coupon_id]);
			if($coupon->is_entity == 1 && strtoupper($coupon->model) == 'ORDER'){
			    //
                  return $this->redirect(['/user-coupon/index']);
            }else{
                return $this->redirect(['/coupon/view','id'=>$customer_coupon->coupon_id]);
            }

		}
		return $this->render('card', ['model' => $model]);
	}

    /**
     * @desc 提货券 激活
     */
	public function actionDeliveryCard()
	{

		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		if (!Yii::$app->user->identity->telephone_validate) {
			return $this->redirect(['/user/security-set-telephone', 'redirect' => '/user-coupon/delivery-card']);
		}

        //判断时间  1月10号开启------------------------------
        $customer_id = ['32204'];
        if(!in_array(Yii::$app->user->getId(),$customer_id) &&  time() <= strtotime("2020-01-09 23:59:59")){
            return $this->render('delivery-card-status');
        }
        //判断时间  1月10号开启------------------------------

		$model = new CouponCardForm();
		if ($model->load(Yii::$app->request->post()) && $customer_coupon=$model->save()) {
			$this->SendNotice($customer_coupon->customer_coupon_id);
			$coupon = Coupon::findOne(['coupon_id'=>$customer_coupon->coupon_id]);
			if($coupon->is_entity == 1 && strtoupper($coupon->model) == 'ORDER'){
			    //
                  return $this->redirect(['/user-coupon/index']);
            }else{
//                return $this->redirect(['/coupon/view-delivery','id'=>$customer_coupon->coupon_id]);
                return $this->redirect(['/coupon/view-delivery','customer_coupon_id'=>$customer_coupon->customer_coupon_id]);
            }

		}
		return $this->render('delivery-card', ['model' => $model]);
	}
	public function SendNotice($customer_coupon_id)
	{
		if ($open_id = \Yii::$app->user->identity->getWxOpenId()) {
			if ($model = CustomerCoupon::findOne(['customer_coupon_id' => $customer_coupon_id])) {
				$notice = new \common\component\Notice\WxNotice();
				$notice->activecoupon($open_id, Url::to(['/coupon/view','id'=>$model->coupon_id],true),
					[
						'title' => '亲，恭喜您【' . $model->coupon->name."】激活成功！",
						//'total' => $model->coupon->getRealDiscount() . ($model->coupon->type == 'F' ? "元" : "折"),
						'exp_date' => $model->end_time,
						'nickname'=> $model->customer ? $model->customer->firstname :'',
						'remark'=>'点击详情，选择商品立即使用，如有疑问请联系客服！',
					]
				);
			}
		}
	}
	public function actionDetail()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$id = Yii::$app->request->get('id');
		if ($coupon = CustomerCoupon::findOne(['customer_id' => Yii::$app->user->getId(), 'customer_coupon_id' => $id])) {
			$model=CouponProduct::find()->where(['coupon_id'=>$coupon->coupon_id,'status'=>1]);
			$dataProvider = new ActiveDataProvider([
				'query' => $model->orderBy('coupon_product_id desc'),
				'pagination' => [
					'pagesize' => '3',
				]
			]);
			return $this->render('detail', ['coupon'=>$coupon,'model' => $model,'dataProvider' => $dataProvider]);
		} else {
			throw new NotFoundHttpException('没有找到相关页面');
		}
	}
}
