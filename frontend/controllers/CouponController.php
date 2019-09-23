<?php
namespace frontend\controllers;
use api\models\V1\Coupon;
use Yii;
use api\models\V1\CustomerCoupon;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class CouponController extends \yii\web\Controller {
	public function actionIndex()
	{
		$model = Coupon::find()->where(['and', 'status=1', 'is_open=1', 'is_entity=0', "receive_begin_date<='" . date('Y-m-d H:i:s', time()) . "'", "receive_end_date>='" . date('Y-m-d H:i:s', time()) . "'"])->orderBy('date_added desc')->all();
		$data = [];
		if ($model) {
			foreach ($model as $coupon) {
				if ($coupon->model) {
					$data[$coupon->model][] = $coupon;
				}
			}
		}
		return $this->render('index', ['model' => $data]);
	}

	public function actionApply()
	{
		$json = [];
		if (Yii::$app->request->isAjax) {
			if (\Yii::$app->user->isGuest) {
				$json = ['status' => 0, 'redirect' => Url::to(['/site/login', 'redirect' => '/coupon/index'])];
			} else {
				$coupon_id = Yii::$app->request->post('coupon_id');
				if ($model = Coupon::findOne(['coupon_id' => $coupon_id, 'is_open' => 1, 'status' => 1])) {
					if ($model->getUsedStatus(Yii::$app->user->getId())) {
						if (count($model->users) < $model->quantity) {
							if ($model->user_limit) {
								$count = CustomerCoupon::find()->where(['customer_id' => Yii::$app->user->getId(), 'coupon_id' => $model->coupon_id])->count();
								if ($count < $model->user_limit) {
									$status = 1;
									$customer_coupon = new CustomerCoupon();
									$customer_coupon->customer_id = Yii::$app->user->getId();
									$customer_coupon->coupon_id = $model->coupon_id;
									$customer_coupon->is_use = 0;
									if ($model->date_type == 'DAYS') {
										$customer_coupon->start_time = date('Y-m-d H:i:s', time());
										$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $model->expire_seconds);
									} else {
										$customer_coupon->start_time = $model->date_start;
										$customer_coupon->end_time = $model->date_end;
									}
									$customer_coupon->date_added = date('Y-m-d H:i:s', time());
									if (!$customer_coupon->save(false)) {
										$json = ['status' => 0, 'message' => "数据提交错误"];
									} else {
										$json = ['status' => 1, 'message' => "领取成功！"];
									}
								} else {
									$json = ['status' => 0, 'message' => "你已经领过了，不要贪心哟~"];
								}
							}
						} else {
							$json = ['status' => 0, 'message' => "优惠已经抢光了，下次在来吧"];
						}
					} else {
						$json = ['status' => 0, 'message' => "你已领取了，使用后再来吧！"];
					}
				} else {
					$json = ['status' => 0, 'message' => "参数错误"];
				}
			}
		} else {
			$json = ['status' => 0, 'message' => "参数错误"];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $json;
	}
}
