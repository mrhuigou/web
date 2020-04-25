<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */
namespace fx\widgets\Order;
use api\models\V1\CustomerAffiliate;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerPromotion;
use api\models\V1\CustomerPromotionDescription;
use api\models\V1\Order;
use api\models\V1\PrizeChance;
use yii\bootstrap\Widget;

class Complate extends Widget {
	public $model;
	public $coupons;
	public function init()
	{
		if($this->model){
		$order_count = Order::find()->where(['customer_id' =>$this->model->customer_id, 'order_type_code' => ['normal', 'presell']])->andWhere(["or", "order_status_id=2", "sent_to_erp='Y'"])->count("*");
		$order_count = $order_count ? $order_count : 0;
		$cur_promotion = [];
		$sub_query = CustomerPromotion::find()->select('id')->where(['status' => 1])->andWhere(['<=', 'start_time', date("Y-m-d H:i:s")])->andWhere(['>=', 'end_time', date("Y-m-d H:i:s")]);
		if ($model = CustomerPromotionDescription::find()->where(['customer_promotion_id' => $sub_query])->all()) {

			foreach ($model as $value) {
				if ($value->min_order_count && $value->min_order_count > $order_count) {
					continue;
				}
				if ($value->max_order_count && $value->max_order_count < $order_count) {
					continue;
				}
				if ($value->min_order_total>0 && $value->min_order_total > $this->model->total) {
					continue;
				}
				if ($value->max_order_total>0 && $value->max_order_total < $this->model->total) {
					continue;
				}
				$cur_promotion = $value;
				break;
			}
		}
			if ($cur_promotion && $cur_promotion->gift) {
				$this->coupons=$cur_promotion->gift;
			}
		}
	}
	public function run()
	{
		if ($this->model) {
			return $this->render('complate', ['coupons' => $this->coupons,'model'=>$this->model]);
		}
	}
}