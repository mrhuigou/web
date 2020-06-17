<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 10:11
 */
namespace h5\widgets\Product;
use api\models\V1\CouponProduct;
use yii\bootstrap\Widget;

class Coupon extends Widget {
	public $model;
	private $_coupon;

	public function init()
	{
		$ids = [];
		if ($this->model->product) {
			foreach ($this->model->product as $product) {
				$ids[] = $product->product_id;
			}
		}
		$coupon_products = CouponProduct::find()->joinWith(['coupon' => function ($query) {
			$query->andFilterWhere(["<=", "jr_coupon.date_start", date('Y-m-d H:i:s', time())]);
			$query->andFilterWhere([">=", "jr_coupon.date_end", date('Y-m-d H:i:s', time())]);
			$query->andFilterWhere(["=", "jr_coupon.is_open", 1]);
			$query->andFilterWhere(["=", "jr_coupon.status", 1]);
		}])->where(['product_id' => $ids])->orderBy('jr_coupon.date_added desc')->all();
		if ($coupon_products) {
			foreach ($coupon_products as $coupon_product) {
				if($coupon_product->status){
				    if(!$coupon_product->coupon->couponCateToCoupon){
                        continue;
                    }
					$this->_coupon[$coupon_product->coupon_id]=$coupon_product->coupon;
				}
			}

		}
		parent::init();
	}

	public function run()
	{
		if($this->_coupon){
			return $this->render('coupon', ['model' => $this->_coupon]);
		}
	}
}