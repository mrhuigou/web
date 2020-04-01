<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/22
 * Time: 9:09
 */
namespace fx\widgets\Checkout;
use api\models\V1\PromotionHistory;
use yii\bootstrap\Widget;

class StorePromotion extends Widget {
	public $promotion;
	public $coupon_gift;
	public $model;

	public function init()
	{
		if ($this->promotion && $this->promotion->gifts) {
			$datas = [];
			foreach ($this->promotion->gifts as $gift) {
				//促销库存限制过滤 INV、QTY
				if ($gift->uplimit_quantity > 0 ) {
					$count = PromotionHistory::find()->where(['and',
						'promotion_type="OrderGift"', 'status=1',
						'promotion_id=' . $gift->promotion_detail_id,
						'promotion_detail_id=' . $gift->promotion_detail_gift_id,
					])->sum('quantity');
					if ($gift->uplimit_quantity < ($count ? $count + $gift->quantity : $gift->quantity)) {
						continue;
					}

					if ($gift->gift_type!='COUPON' && ($gift_product = \api\models\V1\Product::findOne(['product_code' => $gift->product_code, 'store_code' => $gift->store_code]))) {
						if ($gift_product->productBase->begift == 0 && ($gift_product->getStockCount() < $gift->quantity)) {
							continue;
						}
						$gift_product->quantity = $gift->quantity;
						$datas[] = $gift_product;
					}
				}
			}
			$this->model = $datas;
		}
		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{

		return $this->render('store-promotion', ['model' => $this->model,'coupon_gifts'=>$this->coupon_gift]);
	}
}