<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace fx\widgets\Checkout;
use api\models\V1\PromotionHistory;
use yii\bootstrap\Widget;
class Promotion extends Widget
{   public $promotion;
    public $qty;
    public $datas=[];
    public function init()
    {
        if ($this->promotion  && $this->promotion->behave_gift) {
            if ($gifts = $this->promotion->gifts) {
                foreach ($gifts as $gift) {
                    if ($gift->uplimit_quantity > 0 && $gift->base_number <= $this->qty) {
                        if (!$gift->status) {
                            continue;
                        }
                        if ($gift->type == "PERCENTAGE") {
                            $gift_quantity = intval($this->qty / ($gift->base_number / $gift->quantity));
                        } elseif ($gift->type == "MULTIPLE") {
                            $gift_quantity = intval($this->qty / $gift->base_number) * $gift->quantity;
                        } else {
                            $gift_quantity = $gift->quantity;
                        }
                        if ($gift_quantity == 0) {
                            continue;
                        }
                        if ($gift_quantity > $gift->uplimit_quantity) {
                            $gift_quantity = $gift->uplimit_quantity;
                        }

                        //促销库存限制过滤 INV、QTY
                        if ($gift->uplimit_quantity > 0 ) {
                            $count = PromotionHistory::find()->where(['and',
                                'promotion_type="ProductGift"', 'status=1',
                                'promotion_id=' . $gift->promotion_detail_id,
                                'promotion_detail_id=' . $gift->promotion_detail_gift_id,
                            ])->sum('quantity');
                            if ($gift->uplimit_quantity < ($count ? $count + $gift_quantity : $gift_quantity)) {
                                continue;
                            }
                            if ($gift->gift_type!='COUPON' && ($gift_product = \api\models\V1\Product::findOne(['product_code' => $gift->product_code, 'store_code' => $gift->store_code]))) {
	                            if ($gift_product->productBase->begift == 0 && ($gift_product->getStockCount() < $gift_quantity)) {
		                            continue;
	                            }
                                $gift_product->quantity=$gift_quantity;
                                $this->datas[]=$gift_product;
                            }
                        }
                    }
                }
            }
        }
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('promotion',['datas'=>$this->datas]);
    }
} 