<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/10
 * Time: 16:45
 */
namespace api\models\V1;
use common\component\cart\CartPositionInterface;
use common\component\cart\CartPositionTrait;
use yii\base\Object;

class ProductCartPositionFx extends Object implements CartPositionInterface
{   use CartPositionTrait;
	/**
	 * @var Product
	 */
	public $store_id;
	public $product_id;
	public $product_base_id;
	public $affiliate_plan_id;

	public function getId()
	{
		return md5($this->store_id.$this->product_base_id.$this->product_id.$this->affiliate_plan_id);
	}

	public function getPrice()
	{
		$price=$this->product->vip_price;
		$is_yiyuangou=false;
		if(\Yii::$app->session->get('FirstBuy')==$this->getId()){
			$is_yiyuangou=true;
		}
		if($promotion=$this->getPromotion()){
			//进行价格取值
			if($promotion->pricetype=='REBATEPRICE'){
				$price=bcmul($price,$promotion->rebate,2);
			}else{
				$price=max(0,$promotion->price);
			}
		}else{
			if($is_yiyuangou){
				\Yii::$app->session->remove('FirstBuy');
			}
		}
		return number_format($price,2,'.','');
	}

	/**
	 * @return Product
	 */
	public function getProduct()
	{
		return Product::findOne($this->product_id);
	}
	public function hasStock(){
        $qty=$this->getQuantity();
		if($stock_count=$this->product->getStockCount($qty)){
			if($limit_max_qty=$this->product->getLimitMaxQty(\Yii::$app->user->getId())){
				if($stock_count>=$limit_max_qty){
					$stock_count=$limit_max_qty;
				}
			}
		}else{
			$stock_count=0;
		}
		if($stock_count<=0){
			return false;
		}

//		if(($promotion=$this->getPromotion()) && $promotion->behave_gift && $promotion->gifts){
//			foreach($promotion->gifts as $gift){
//				if($gift->product_code==$this->product->product_code){
//					if($qty>$gift->base_number){
//						$stock_count=$stock_count-(intval($qty/$gift->base_number)*$gift->quantity);
//					}
//					break;
//				}
//			}
//		}
		return $stock_count>=$qty;
	}
	public function getPromotion(){
		$cur_promotion=[];
		$customer_id=\Yii::$app->user->getId();
		if($promotions=$this->product->getPromotions()){
			$price=$this->product->vip_price;
			$product_count=$this->getQuantity();
			foreach($promotions as $promotion){

				//进行小时约束过滤
				if($promotion->need_hour && (strtotime(date("Y-m-d ".$promotion->date_start,time()))>time() || strtotime(date("Y-m-d ".$promotion->date_end,time()))< time())){
					continue;
				}

				if($product_count) {
					//进行数量、金额、数量金额、不限过滤
					if ($promotion->stairtype == "QTY" && ($promotion->begin_quantity > $product_count || $promotion->end_quantity < $product_count)) {
						continue;
					}
					$order_total = bcmul($price, $product_count, 2);
					if ($promotion->stairtype == "MONEY" && ($promotion->begin_amount > $order_total || $promotion->end_amount < $order_total)) {
						continue;
					}
					if ($promotion->stairtype == "ALL" && ($promotion->begin_quantity > $product_count || $promotion->end_quantity < $product_count || $promotion->begin_amount > $order_total || $promotion->end_amount < $order_total)) {
						continue;
					}
					//促销库存限制过滤 INV、QTY
					if ($promotion->uplimit_type == 'QTY') {
						$count = PromotionHistory::find()->where(['and',
							'promotion_type="SINGLE"', 'status=1',
							'promotion_id=' . $promotion->promotion_id,
							'promotion_detail_id=' . $promotion->promotion_detail_id,
						])->sum('quantity');
						if ($promotion->uplimit_quantity < ($count ? ($count + $product_count) : $product_count)) {
							continue;
						}
					}
					//进行购买频次过滤 DAY，NONE，PROMOTION_TIME，ORDER
					if ($promotion->stop_buy_type == "DAY") {
						$count = PromotionHistory::find()->where(['and',
							'promotion_type="SINGLE"', "customer_id='" . $customer_id."'", 'status=1',
							'promotion_id=' . $promotion->promotion_id,
							'promotion_detail_id=' . $promotion->promotion_detail_id,
							'date_added>' . "'" . date('Y-m-d 00:00:00', time()) . "'", 'date_added<' . "'" . date('Y-m-d 23:59:59', time()) . "'"
						])->sum("quantity");
						if ($promotion->stop_buy_quantity < (($count?$count:0) + $product_count)) {
							continue;
						}
					}
					if ($promotion->stop_buy_type == "PROMOTION_TIME") {
						$count = PromotionHistory::find()->where(['and',
							'promotion_type="SINGLE"', "customer_id='" . $customer_id."'", 'status=1',
							'promotion_id=' . $promotion->promotion_id,
							'promotion_detail_id=' . $promotion->promotion_detail_id,
							'date_added>' . "'" . $promotion->begin_date . "'", 'date_added<' . "'" . $promotion->end_date . "'"
						])->sum("quantity");
						if ($promotion->stop_buy_quantity < (($count?$count:0) + $product_count)) {
							continue;
						}
					}
					if ($promotion->stop_buy_type == "ORDER") {
						if ($promotion->stop_buy_quantity < $product_count) {
							continue;
						}
					}
					if (($subject = $promotion->promotion->subject) && $subject == 'YIYUANGOU') {
						if(!\Yii::$app->session->get('FirstBuy')){
							\Yii::$app->session->set('FirstBuy',$this->getId());
						}
						//检查历史
						$model = Promotion::find()->select('promotion_id')->where(['subject' => 'YIYUANGOU'])->all();
						$promotion_ids = [];
						if ($model) {
							foreach ($model as $val) {
								$promotion_ids[] = $val->promotion_id;
							}
						}
						$count = PromotionHistory::find()->where(['and',
							'promotion_type="SINGLE"', "customer_id='" . $customer_id."'", 'status=1',
							['promotion_id' => $promotion_ids],
						])->count();
						if ($count) {
							continue;
						}
						if (\Yii::$app->session->get('FirstBuy') !== $this->getId()) {
							continue;
						}
					}
				}
				$cur_promotion=$promotion;
				break;
			}
		}
		return $cur_promotion;
	}
}