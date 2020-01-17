<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */
namespace h5\widgets\Checkout;
use api\models\V1\CustomerCoupon;
use api\models\V1\Store;
use yii\base\InvalidParamException;
use yii\bootstrap\Widget;

class Coupon extends Widget {
	public $store_id;
	public $minbookcash;
	public $shipping;
	public $product;
	public $sub_total;
	private $model;

	public function init()
	{
		if (!$store = Store::findOne(['store_id' => $this->store_id])) {
			throw new InvalidParamException("参数错误");
		}else{
		    $this->minbookcash = $store->minbookcash;
        }
		$user_copons = $this->getUserCoupon($store);
		if ($user_copons) {
			$group_data = [];
			$order_data = [];
			foreach ($user_copons as $value) {
				if ($value->coupon->model == 'ORDER') {
					$order_data[] = $value;
				} else {
					$group_data[] = $value;
				}
			}
			$this->model = [
				'data' => $group_data,
				'order' => $order_data,
				'cur' => [],
			];
		}
		parent::init();
	}

	//取得用户当前店铺有效优惠券
	public function getUserCoupon()
	{
		$coupon_data = [];
		$model = CustomerCoupon::find()->joinWith(['coupon' => function ($query) {
			$query->andFilterWhere(["=", "jr_coupon.status", 1]);
		}])->where(['customer_id' => \Yii::$app->user->identity->getId(), 'is_use' => 0, 'store_id' => $this->store_id])
			->andWhere(["<=", "start_time", date('Y-m-d H:i:s', time())])
			->andWhere([">=", "end_time", date('Y-m-d H:i:s', time())])
			->orderBy('jr_coupon.discount desc, end_time asc ,customer_coupon_id desc')->groupBy('coupon_id')->all();
		if ($model) {
			foreach ($model as $value) {
                $sub_total_default_status = true;
				$sub_qty = 0; //统计购物数量
				$sub_total = 0;//统计商品小计
				//判断此券是否限制包装商品
				if ($value->coupon->product) {
                    $sub_total_default_status = false;
					$coupon_product = [];
					foreach ($value->coupon->product as $product) {
						if ($product->status) {
							$coupon_product[] = $product->product_id;
						}
					}
                    if ($coupon_product) {
                        foreach ($this->product as $product) {
                            if (in_array($product->product->product_id, $coupon_product)) {
                                $sub_qty += $product->quantity;
                                $sub_total = bcadd($sub_total, $product->getCost(), 2);
                            }
                        }
                    }
				}

                if($value->coupon->except_category) {
                    $sub_total_default_status = false;
                    $except_array = [];
                    if ($value->coupon->except_category) {
                        $except_array = explode(',', $value->coupon->except_category);
                    }
                    foreach ($this->product as $product) {
                        if (!in_array($product->product->productBase->category_id, $except_array)) {
                            $sub_qty += $product->quantity;
                            $sub_total = bcadd($sub_total, $product->getCost(), 2);
                        }
                    }
                }
                if($sub_total_default_status){
                    $sub_qty = $this->SubQty($this->product); //统计购物数量
                    $sub_total = $this->SubTotal($this->product);//统计商品小计
                }
				if ($value->coupon->limit_min_quantity && $value->coupon->limit_min_quantity > $sub_qty) {
					continue;
				}
				if ($value->coupon->limit_max_quantity && $value->coupon->limit_max_quantity < $sub_qty) {
					continue;
				}
				if ($value->coupon->total > 0 && $value->coupon->total > $sub_total) {
					continue;
				}
				if ($value->coupon->limit_max_total > 0 && $value->coupon->limit_max_total < $sub_total) {
					continue;
				}
				//判断券的使用次数
				$count = $value->coupon->historyCount;
				if ($value->coupon->quantity > 0 && $count >= $value->coupon->quantity) {
					continue;
				}
				$coupon_data[] = $value;
			}
		}
		return $coupon_data;
	}
	//全局优惠券
	public function getGlobalCoupon(){
		$coupon_data = [];
		$model = \api\models\V1\Coupon::find()->where(['status'=>1,'store_id' => $this->store_id])
			->andWhere(["<=", "date_start", date('Y-m-d H:i:s', time())])
			->andWhere([">=", "date_end", date('Y-m-d H:i:s', time())])
			->orderBy('discount desc, date_end asc ,coupon_id desc')->groupBy('coupon_id')->all();
		if($model){
			foreach ($model as $value) {
				$sub_qty = 0; //统计购物数量
				$sub_total = 0;//统计商品小计
				//判断此券是否限制包装商品
				if ($value->product) {
					$coupon_product = [];
					foreach ($value->product as $product) {
						if ($product->status) {
							$coupon_product[] = $product->product_id;
						}
					}
					if ($coupon_product) {
						$status = false;
						foreach ($this->product as $product) {
							if (in_array($product->product->product_id, $coupon_product)) {
								$sub_qty += $product->quantity;
								$sub_total = bcadd($sub_total, $product->getCost(), 2);
								$status = true;
							}
						}
						if (!$status) {
							continue;
						}
					}
				} else {
					$sub_qty = $this->SubQty($this->product); //统计购物数量
					$sub_total = $this->SubTotal($this->product);//统计商品小计
				}

				if ($value->limit_min_quantity && $value->limit_min_quantity > $sub_qty) {
					continue;
				}
				if ($value->limit_max_quantity && $value->limit_max_quantity < $sub_qty) {
					continue;
				}
				if ($value->total > 0 && $value->total > $sub_total) {
					continue;
				}
				if ($value->limit_max_total > 0 && $value->limit_max_total < $sub_total) {
					continue;
				}
				//判断券的使用次数
				$count = count($value->history);
				if ($value->quantity > 0 && $count >= $value->quantity) {
					continue;
				}
				if($value->realDiscount >= $sub_total){
                    $tmp_sub_total = $sub_total;
                }else{
                    $tmp_sub_total = $value->realDiscount;
                }

				$coupon_data[] = $value;
			}
		}
		return $coupon_data;
	}

	//店铺商品金额
	protected function SubTotal($cart)
	{
		$sub_total = 0;
		if ($cart) {
			foreach ($cart as $value) {
				$sub_total += $value->getCost();
			}
		}
		return $sub_total;
	}

	//店铺商品数量
	protected function SubQty($cart)
	{
		$sub_qty = 0;
		if ($cart) {
			foreach ($cart as $value) {
				$sub_qty += $value->quantity;
			}
		}
		return $sub_qty;
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		if ($this->model) {
			return $this->render('coupon', ['model' => $this->model, 'store_id' => $this->store_id,'minbookcash' => $this->minbookcash,'shipping' =>$this->shipping]);
		}else{
            return $this->render('coupon-empty',['sub_total'=> $this->sub_total,'shipping'=> $this->shipping]);
        }
	}
} 