<?php

namespace api\models\V1;
use Yii;

/**
 * This is the model class for table "{{%product_base}}".
 *
 * @property string $product_base_id
 * @property string $product_base_code
 * @property string $ename
 * @property string $manufacturer_code
 * @property integer $manufacturer_id
 * @property integer $store_id
 * @property string $store_code
 * @property string $life
 * @property string $deliverycode
 * @property string $image
 * @property integer $beintoinv
 * @property string $typecode
 * @property integer $begift
 * @property integer $bemanage
 * @property integer $bepresell
 * @property integer $sort_order
 * @property integer $is_merge
 * @property integer $hot_sort_order
 * @property string $date_added
 * @property string $date_modified
 * @property integer $shipping
 * @property integer $bevirtual
 * @property string $verifycodetype
 * @property string $back_rebate
 * @property integer $expire_time
 * @property string $product_model
 * @property string $spec_array
 * @property integer $becycle
 * @property integer $bedisplaylife
 * @property integer $category_id
 * @property integer $can_not_return
 */
class ProductBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_base}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//       //     [['product_base_code', 'manufacturer_code', 'manufacturer_id', 'store_id', 'store_code', 'life', 'deliverycode', 'image', 'beintoinv', 'begift', 'bemanage', 'bepresell', 'sort_order', 'hot_sort_order', 'date_added', 'date_modified', 'expire_time'], 'required'],
//            [['manufacturer_id', 'store_id', 'beintoinv', 'begift', 'bemanage', 'bepresell', 'sort_order', 'is_merge', 'hot_sort_order', 'shipping', 'bevirtual', 'expire_time', 'becycle'], 'integer'],
//            [['date_added', 'date_modified'], 'safe'],
//            [['back_rebate'], 'number'],
//            [['spec_array'], 'string'],
//            [['product_base_code', 'manufacturer_code', 'store_code', 'verifycodetype', 'product_model'], 'string', 'max' => 32],
//            [['ename', 'life', 'deliverycode', 'image'], 'string', 'max' => 255],
//            [['typecode'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'ename' => 'Ename',
            'manufacturer_code' => 'Manufacturer Code',
            'manufacturer_id' => 'Manufacturer ID',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'life' => 'Life',
            'deliverycode' => 'Deliverycode',
            'image' => 'Image',
            'beintoinv' => 'Beintoinv',
            'typecode' => '产品类型',
            'begift' => 'Begift',
            'bemanage' => 'Bemanage',
            'bepresell' => 'Bepresell',
            'sort_order' => 'Sort Order',
            'is_merge' => '1合并，0不合并',
            'hot_sort_order' => 'Hot Sort Order',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'shipping' => '1，代表需要配送，0，代表不需要配送',
            'bevirtual' => '0代表实物，1，代表虚拟商品',
            'verifycodetype' => 'Verifycodetype',
            'back_rebate' => '返还比例（倍数）',
            'expire_time' => '过期时间（天）',
            'product_model' => 'NORMAL:普通商品,VIRTUAL：虚拟商品,FRESH：新鲜商品，DININGTABLE：餐位，DISHES：菜品，DELIVERY：外卖，FOODDELIVERY：菜品和外卖',
            'spec_array' => 'Spec Array',
            'becycle' => '是否支持周期购',
            'can_not_return' => '是否支持7天无理由退货'
        ];
    }
    public function getDescription(){
        return $this->hasOne(ProductBaseDescription::className(), ['product_base_id' => 'product_base_id']);
    }
    public function getPrice(){
        $price=$this->hasMany(Product::className(),['product_base_id' => 'product_base_id'])->andWhere(['beintoinv'=>1])->min('vip_price');
        if($products=$this->hasMany(Product::className(),['product_base_id' => 'product_base_id'])->andWhere(['jr_product.beintoinv'=>1])->all()){
            foreach($products as $product){
              if($price>$product->getPrice()){
                  $price=$product->getPrice();
              }
            }
        }
       return $price;
    }
    public function getVip_price(){
        return $this->hasMany(Product::className(),['product_base_id' => 'product_base_id'])->andWhere(['beintoinv'=>1])->min('vip_price');
    }
    public function getSale_price(){
        return $this->hasMany(Product::className(),['product_base_id' => 'product_base_id'])->andWhere(['beintoinv'=>1])->min('price');
    }
    public function getAttibute(){
        return $this->hasMany(ProductBaseAttribute::className(),['product_base_id'=>'product_base_id']);
    }
    public function getSearchAttibute(){
        $data=[];
        $model=$this->attibute;
        if($model){
            foreach($model as $attr){
                if($attr->text){
                    $data[]=$attr->attribute_code."-".$attr->text;
                }
            }
        }
        return $data;
    }
    public function getRecord(){
        return $this->hasMany(OrderProduct::className(),['product_base_id'=>'product_base_id'])->where(['product_base_id'=>$this->product_base_id])->sum('quantity');
    }
    public function getFavourite(){
        return $this->hasMany(CustomerCollect::className(),['product_base_id'=>'product_base_id'])->where(['product_base_id'=>$this->product_base_id])->count('*');
    }
    public function getReview(){
        return $this->hasMany(Review::className(),['product_base_id'=>'product_base_id'])->where(['product_base_id'=>$this->product_base_id])->count('*');
    }

    public function getCategory(){
        return $this->hasOne(Category::className(),['category_id'=>'category_id']);
    }
    public function getDefaultImage(){
        if($data=$this->imagelist){
            return $data[0];
        }else{
            return "";
        }
    }
    public function getItemRelation(){
        return $this->hasMany(ProductBase::className(),['manufacturer_id'=>'manufacturer_id','category_id'=>'category_id'])->andWhere(['and','beintoinv=1','product_base_id<>'.$this->product_base_id]);

    }
    public function getImages(){
        return $this->hasMany(ProductImage::className(),['product_base_id'=>'product_base_id'])->orderBy('sort_order asc');
    }
    public function getImagelist(){
        $data=[];
        if($this->image){
            $data[] = $this->image;
        }
        if($this->product){
            foreach($this->product as $value){
                if($value->image && $value->beintoinv){
                    $data[]=$value->image;
                    if($value->images){
                        foreach($value->images as $val){
                            $data[]=$val->image;
                        }
                    }
                }
            }
        }
    return array_unique($data);
    }
    public function getSku(){
        $result=[];
        if($this->product){
            $datas=[];
            foreach($this->product as $product){
                if($product->sku){
                    $datas[]=explode(';',$product->sku);
                }else{
                    $datas[]=['0:'.$product->product_id];
                }
            }
            $sku_array=[];
            foreach($datas as $data){
              foreach($data as $value){
                  list($att_id,$att_value_id)=explode(':',$value);
                  $sku_array[$att_id][$att_value_id]= $value;
              }
            }

            foreach($sku_array as $key=>$value){
                if($key){
                    $content=[];
                    foreach($value as $k=>$v){
                        $option_value=OptionValueDescription::findOne(['option_value_id'=>$k,'option_id'=>$key]);
                        if($option_value){
                            $content[]=[
                                'id'=>$k,
                                'name'=>$option_value->name,
                                'value'=>$v
                            ];
                        }
                    }
                    $option=OptionDescription::findOne(['option_id'=>$key]);
                    if($option){
                        $result[]=[
                        'id'=>$key,
                        'name'=>$option->name,
                        'content'=>$content
                         ];
                    }
                }else{
                    $content=[];
                    foreach($value as $key=>$v){
                        $product=Product::findOne(['product_id'=>$key]);
                        if($product){
                            $name_str=$product->unit?$product->unit:"默认";
                            $name_str.=$product->format?"(".$product->format.")":"";
                            $content[]=[
                                'id'=>$key,
                                'name'=>$name_str,
                                'value'=>$v
                            ];
                        }
                    }
                    $result[]=[
                        'id'=>0,
                        'name'=>'包装/规格',
                        'content'=>$content
                    ];
                }
            }
        }
        return $result;
    }
    public function getSkuKeys(){
        $data=[];
        if($this->sku){
            foreach($this->sku as $key=>$value){
                if($value && $value['content']){
                    foreach($value['content'] as $v){
                        $data['sku_keys'][$key]=isset($data['sku_keys'][$key])?$data['sku_keys'][$key]:[];
                        array_push($data['sku_keys'][$key],$v['value']);
                    }
                }
            }
        }
        return $data;
    }

    public function getSkuData(){
        $data=[];
        if($this->product){
            foreach($this->product as $product){
                $stock_type = $this->category ? $this->category->stock_type : 'NUMBER';
                if(Yii::$app->session->get("ShowStock")){
                    $stock_type = 'NUMBER';
                }
                if($product->sku){
                    $data[$product->sku]=[
                        'price'=>number_format($product->getPrice(),2),
                        'sale_price'=>number_format($product->price,2),
                        'count'=>max(0,$product->stockCount),
                        'format'=>$product->format,
                        'code'=>$product->product_code,
                        'stock_type' => $stock_type,
                        'low_limit' => $this->category ? $this->category->low_limit : 0,
                        'shipping'=>$product->baoyou,
                        'promotion'=>$product->promotion?1:0,
                    ] ;
                }else{
                    $data['0:'.$product->product_id]=[
                        'price'=>number_format($product->getPrice(),2),
                        'sale_price'=>number_format($product->price,2),
                        'count'=>$product->stockCount,
                        'stock_type' => $stock_type,
                        'low_limit' => $this->category ? $this->category->low_limit : 0,
                        'format'=>$product->format,
                        'code'=>$product->product_code,
                        'shipping'=>$product->baoyou,
                        'promotion'=>$product->promotion?1:0,
                    ];
                }

            }
        }
        return $data;
    }

    public function getProduct(){
        return $this->hasMany(Product::className(),['product_base_id'=>'product_base_id'])->andWhere(['beintoinv'=>1])->orderBy("sort_order asc");
    }
    public function getProductDate(){
        $date="";
        if($this->getProduct()){
            foreach($this->product as $product){
                $date=$product->getProductDate();
            }
        }
        return $date;
    }
    public function getPromotion(){
            $promotion=[];
            if($this->getProduct()){
            foreach($this->product as $product){
                if($product->promotions){
                    foreach($product->promotions as $value){
                        $promotion[]=$value;
                    }
                }
            }
        }
        return $promotion;
    }
    public function getBaoyou(){
        $status=false;
        if($this->product){
            foreach($this->product as $product){
                if($product->baoyou){
                   $status=true;
                    break;
                }
            }
        }
        return $status;
    }
      public static function populateFromSolr($result){
        return ProductBase::findOne(['product_base_id'=>$result->id]);
    }
    public function getOnline_status(){
        $status=false;
        if($this->product && $this->shop && $this->shop->online==1){
            foreach($this->product as $product){
                if($product->beintoinv){
                    $status=true;
                    break;
                }
            }
        }
    return $status;
    }
    public function getStockCount(){
        $stock_count=0;
        if($this->bepresell){
            $stock_count=9999;
        }else{
            foreach($this->product as $product){
                if($product->beintoinv=='1'){
                    $stock_count+=$product->stockCount;
                }
            }
        }
        return $stock_count;
    }
    public function getShop(){
        return $this->hasOne(Store::className(),['store_id'=>'store_id']);
    }

    public function getCoupon(){
        $ids = [];
        if ($this->getProduct()) {
            foreach ($this->getProduct() as $product) {
                $ids[] = $product->product_id;
            }
        }
        $coupon_products = CouponProduct::find()->joinWith(['coupon' => function ($query) {
            $query->andFilterWhere(["<=", "jr_coupon.date_start", date('Y-m-d H:i:s', time())]);
            $query->andFilterWhere([">=", "jr_coupon.date_end", date('Y-m-d H:i:s', time())]);
            $query->andFilterWhere(["=", "jr_coupon.is_open", 1]);
            $query->andFilterWhere(["=", "jr_coupon.status", 1]);
        }])->where(['product_id' => $ids])->all();
        if ($coupon_products) {
            foreach ($coupon_products as $coupon_product) {
                if($coupon_product->status){
                    $coupon[$coupon_product->coupon_id]=$coupon_product->coupon;
                }
            }

        }
        return $coupon;
    }
}
