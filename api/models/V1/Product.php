<?php

namespace api\models\V1;
use common\component\cart\CartPositionProviderInterface;
use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property string $product_id
 * @property string $product_code
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $store_id
 * @property string $store_code
 * @property string $upc
 * @property string $uom
 * @property string $sku_name
 * @property string $sku
 * @property string $ean
 * @property string $jan
 * @property string $isbn
 * @property string $mpn
 * @property string $image
 * @property string $vip_price
 * @property string $price
 * @property integer $status
 * @property string $unit
 * @property integer $quantity
 * @property integer $convertfigure
 * @property integer $tax_class_id
 * @property string $format
 * @property string $date_available
 * @property string $viewed
 * @property integer $minimum
 * @property integer $subtract
 * @property string $weight
 * @property integer $weight_class_id
 * @property string $volume
 * @property string $length
 * @property string $width
 * @property string $height
 * @property integer $length_class_id
 * @property integer $begift
 * @property integer $beintoinv
 * @property integer $bemanage
 * @property integer $besale
 * @property integer $beclearance
 * @property integer $bepresell
 * @property integer $hot_sort_order
 * @property integer $sort_order
 * @property string $date_added
 * @property string $date_modified
 * @property string $model
 * @property string $points
 * @property integer $shipping
 * @property integer $stock_status_id
 * @property integer $favourite
 * @property string $product_model
 * @property integer $min_sale_qty
 * @property integer $max_sale_qty
 */
class Product extends \yii\db\ActiveRecord implements CartPositionProviderInterface
{

    private $product_date;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_base_id', 'store_id', 'status', 'convertfigure', 'tax_class_id', 'viewed', 'minimum', 'subtract', 'weight_class_id', 'length_class_id', 'begift', 'beintoinv', 'bemanage', 'besale','beclearance', 'bepresell', 'hot_sort_order', 'sort_order', 'shipping', 'stock_status_id', 'favourite','min_sale_qty','max_sale_qty'], 'integer'],
            [['sku'], 'string'],
            [['vip_price', 'price', 'weight', 'volume', 'length', 'width', 'height', 'points'], 'number'],
            [['date_available', 'date_added', 'date_modified'], 'safe'],
            [['product_code', 'product_base_code', 'store_code', 'product_model'], 'string', 'max' => 32],
            [['upc', 'uom', 'sku_name', 'image'], 'string', 'max' => 255],
            [['ean'], 'string', 'max' => 14],
            [['jan', 'isbn'], 'string', 'max' => 13],
            [['mpn', 'model'], 'string', 'max' => 64],
            [['unit'], 'string', 'max' => 50],
            [['format'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'upc' => 'Upc',
            'uom' => 'Uom',
            'sku_name' => 'Sku Name',
            'sku' => 'Sku',
            'ean' => 'Ean',
            'jan' => 'Jan',
            'isbn' => 'Isbn',
            'mpn' => 'Mpn',
            'image' => 'Image',
            'vip_price' => 'Vip Price',
            'price' => 'Price',
            'status' => 'Status',
            'unit' => 'Unit',
            'convertfigure' => 'Convertfigure',
            'tax_class_id' => 'Tax Class ID',
            'format' => 'Format',
            'date_available' => 'Date Available',
            'viewed' => 'Viewed',
            'minimum' => 'Minimum',
            'subtract' => 'Subtract',
            'weight' => 'Weight',
            'weight_class_id' => 'Weight Class ID',
            'volume' => 'Volume',
            'length' => 'Length',
            'width' => 'Width',
            'height' => 'Height',
            'length_class_id' => 'Length Class ID',
            'begift' => 'Begift',
            'beintoinv' => 'Beintoinv',
            'bemanage' => 'Bemanage',
            'beclearance' => 'Beclearance',
            'bepresell' => 'Bepresell',
            'hot_sort_order' => 'Hot Sort Order',
            'sort_order' => 'Sort Order',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'model' => 'Model',
            'points' => 'Points',
            'shipping' => 'Shipping',
            'stock_status_id' => 'Stock Status ID',
            'favourite' => '用户喜欢数量',
            'product_model' => 'NORMAL:普通商品,VIRTUAL：虚拟商品,FRESH：新鲜商品，DININGTABLE：餐位，DISHES：菜品，DELIVERY：外卖，FOODDELIVERY：菜品和外卖',
        ];
    }
    public function getCartPosition($params = []){
        return \Yii::createObject([
            'class' => 'api\models\V1\ProductCartPosition',
            'store_id'=>$this->store_id,
            'product_base_id'=>$this->product_base_id,
            'product_id' => $this->product_id,
        ]);
    }

    public function getCartPositionFx($params = []){
        return \Yii::createObject([
            'class' => 'api\models\V1\ProductCartPositionFx',
            'store_id'=>$this->store_id,
            'product_base_id'=>$this->product_base_id,
            'product_id' => $this->product_id,
            'affiliate_plan_id' => $params['affiliate_plan_id'],
        ]);
    }
    public function getDescription(){
        return $this->hasOne(ProductBaseDescription::className(), ['product_base_id' => 'product_base_id']);
    }
    public function getProductBase(){
        return $this->hasOne(ProductBase::className(), ['product_base_id' => 'product_base_id']);
    }
    /**
     * 判断促销记录是否符合
     * 单品促销记录
     * @param  $promotion_type_code：促销类型
     * @param  $promotion_detail：促销记录
     * @param  $total：订单/产品金额
     * @param  $limit_quantity：限制数量
     * @param  $quantity：购买数量，针对库存量
     */
    public function getPrice(){
        $price=$this->vip_price;
        if($promotion=$this->getPromotion()){
            //进行价格取值
            if($promotion->pricetype=='REBATEPRICE'){
                $price=bcmul($price,$promotion->rebate,2);
            }else{
                $price=max(0,$promotion->price);
            }
        }
        return floatval(number_format($price,2,'.',''));
    }
    public function getPromotion(){
        $customer_id=0;
        $product_count=0;
        $cur_promotion=[];
        if($promotions=$this->getPromotions()){
            $price=$this->vip_price;
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
                        ])->count();
                        if ($promotion->stop_buy_quantity < ($count + $product_count)) {
                            continue;
                        }
                    }
                    if ($promotion->stop_buy_type == "PROMOTION_TIME") {
                        $count = PromotionHistory::find()->where(['and',
                            'promotion_type="SINGLE"', "customer_id='" . $customer_id."'", 'status=1',
                            'promotion_id=' . $promotion->promotion_id,
                            'promotion_detail_id=' . $promotion->promotion_detail_id,
                            'date_added>' . "'" . $promotion->begin_date . "'", 'date_added<' . "'" . $promotion->end_date . "'"
                        ])->count();
                        if ($promotion->stop_buy_quantity < ($count + $product_count)) {
                            continue;
                        }
                    }
                    if ($promotion->stop_buy_type == "ORDER") {
                        if ($promotion->stop_buy_quantity < $product_count) {
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

    public function getPromotions(){
        $promotions=PromotionDetail::find()->joinWith([
            'promotion'=>function($query){
                $query ->andFilterWhere(["jr_promotion.type"=>'SINGLE','jr_promotion.status'=>1]);
            }
        ])->where(['jr_promotion_detail.product_id'=>$this->product_id,'jr_promotion_detail.status'=>1,'jr_promotion_detail.store_id'=>$this->store_id])
            ->andWhere(['and',"jr_promotion_detail.begin_date<='".date("Y-m-d H:i:s",time())."'","jr_promotion_detail.end_date>='".date("Y-m-d H:i:s",time())."'"])
            ->orderBy('jr_promotion_detail.promotion_detail_id desc')->all();
        return $promotions;
    }
    public function getStoreToWarehouse(){
        return $this->hasMany(StoreToWarehouse::className(),['store_id'=>'store_id']);
    }
	public function getLimitMaxQty($customer_id=0){
		$qty=0;
		if($promotions=$this->getPromotions()){
			foreach($promotions as $promotion){
				//进行小时约束过滤
				if($promotion->need_hour && (strtotime(date("Y-m-d ".$promotion->date_start,time()))>time() || strtotime(date("Y-m-d ".$promotion->date_end,time()))< time())){
					continue;
				}
				//进行数量、金额、数量金额、不限过滤
				if ($promotion->stop_buy_type  && $promotion->stop_buy_quantity) {
						//进行购买频次过滤 DAY，NONE，PROMOTION_TIME，ORDER
					if ($promotion->stop_buy_type == "DAY") {
						$count = PromotionHistory::find()->where(['and',
							'promotion_type="SINGLE"', "customer_id='" . $customer_id."'", 'status=1',
							'promotion_id=' . $promotion->promotion_id,
							'promotion_detail_id=' . $promotion->promotion_detail_id,
							'date_added>' . "'" . date('Y-m-d 00:00:00', time()) . "'", 'date_added<' . "'" . date('Y-m-d 23:59:59', time()) . "'"
						])->sum("quantity");
						$qty=max(0,($promotion->stop_buy_quantity-($count?$count:0)));
						break;
					}
					if ($promotion->stop_buy_type == "PROMOTION_TIME") {
						$count = PromotionHistory::find()->where(['and',
							'promotion_type="SINGLE"', "customer_id='" . $customer_id."'", 'status=1',
							'promotion_id=' . $promotion->promotion_id,
							'promotion_detail_id=' . $promotion->promotion_detail_id,
							'date_added>' . "'" . $promotion->begin_date . "'", 'date_added<' . "'" . $promotion->end_date . "'"
						])->sum("quantity");
						$qty=max(0,($promotion->stop_buy_quantity-($count?$count:0)));
						break;
					}
					if ($promotion->stop_buy_type == "ORDER") {
						$qty=max(0,$promotion->stop_buy_quantity);
						break;
					}

				}
			}
		}
		if(!$qty){
			$qty=$this->max_sale_qty;
		}
		return $qty;
	}
    public function getStockCount($cart_quantity = 0){
        $total=0;
        $productmodel = Product::findOne($this->product_id); //不能直接使用 比如购物车中已经存好的商品数据
        if($productmodel->productStore && $productmodel->productStore->status==1) {
            if ($productmodel->bepresell) {
                $total = 9999;
            } else {
                $warehouse = [];
                foreach ($this->getStoreToWarehouse()->all() as $w) {
                    $warehouse[] = $w->warehouse_id;
                }
                $model = WarehouseStock::find()->where(['product_code' => $this->product_code])->andWhere(['warehouse_id' => $warehouse]);
                if ($model->one()) {
                    $obj=$model->one();
                    $this->product_date=$obj->product_date;
                    $total = $model->sum('quantity') - $model->sum('tmp_qty');
                }
                if($cart_quantity){
                    $promotion = $this->promotion;
                    if($promotion && $promotion->behave_gift && $promotion->gifts){
                        foreach($promotion->gifts as $gift){
                            if($gift->product_code==$this->product_code){
                                if($cart_quantity>=$gift->base_number){
                                    $total=$total-floor(($cart_quantity/$gift->base_number)*$gift->quantity);
                                }
                                //  $stock_count=intval($stock_count/($gift->base_number+$gift->quantity));
                                break;
                            }
                        }
                    }
                }

            }
            if (!$productmodel->beintoinv) {
                $total = 0;
            }
        }else{
            $total = 0;
        }
        if(!$this->besale){
            $total=0;
        }
        return max(0,$total);
    }
    public function getProductDate(){
         $this->getStockCount();
        return $this->product_date;
    }

    public function hasStock(){
        $stock_count=$this->getStockCount();
        $qty=$this->getQuantity();
        if($this->promotion && $this->promotion->behave_gift && $this->promotion->gifts){
            foreach($this->promotion->gifts as $gift){
                if($gift->product_code==$this->product_code){
	                if($qty>$gift->base_number){
		                $stock_count=$stock_count-(($qty/$gift->base_number)*$gift->quantity);
	                }
                  //  $stock_count=intval($stock_count/($gift->base_number+$gift->quantity));
                    break;
                }
            }
        }
        return $stock_count>=$qty;
    }
    public function getBaoyou(){
        $status=false;
        if($model=$this->hasMany(PromotionDetail::className(),['product_id'=>'product_id'])->andOnCondition(['and','status=1',"begin_date<='".date("Y-m-d H:i:s",time())."'","end_date>='".date("Y-m-d H:i:s",time())."'"])->all()){
          foreach($model as $list){
              if($list->promotion->type=='FREEDELIVERY'){
                  $status=true;
                  break;
              }
          }
        }
        return $status;
    }
    public function getProductStore(){
        return $this->hasOne(Store::className(),['store_id'=>'store_id']);
    }
    public function getSku(){
        $data="";
        if($this->sku){
            $sku=explode(";",$this->sku);
            foreach($sku as $value){
                list($option_id,$option_value_id)=explode(":",$value);
                $option=OptionDescription::findOne(['option_id'=>$option_id]);
                $option_value=OptionValueDescription::findOne(['option_value_id'=>$option_value_id]);
                $data.=($option?$option->name.":":"默认").($option_value?$option_value->name.";":"");
            }
        }else{
            $data=$this->unit.($this->format?"(".$this->format.")":"");
        }
        return $data;
    }
    public function getImages(){
        return $this->hasMany(ProductImage::className(),['product_id'=>'product_id']);
    }
    public function getCommission($customer_id,$total){
		$commission=0;
		if($user=CustomerAffiliate::findOne(['customer_id'=>$customer_id,'status'=>1])){
			$model=ProductCommission::find()->where(['product_id'=>$this->product_id,'status'=>1])->andWhere(['>=','UNIX_TIMESTAMP(start_time)',time()])->andWhere(['<=','UNIX_TIMESTAMP(end_time)',time()])->one();
			if($model){
				if($model->commission_type=='P'){
					$commission=bcmul($model->commission,$total,2);
				}else{
					$commission=$model->commission;
				}
			}else{
				$commission=bcmul($user->commission,$total,2);
			}
		}
		return $commission;
    }
    public function getWarehouseStock(){
        return $this->hasOne(WarehouseStock::className(),['product_code'=>'product_code']);
    }
}
