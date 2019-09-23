<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/9
 * Time: 13:53
 */

namespace api\modules\shop\controllers;
use api\models\V1\OptionDescription;
use api\models\V1\OptionValueDescription;
use api\models\V1\OrderProduct;
use api\models\V1\Product;
use api\models\V1\Promotion;
use api\models\V1\PromotionDetail;
use api\models\V1\Review;
use api\models\V1\WarehouseStock;
use yii\helpers\Url;
use yii\web\Controller;
use api\models\V1\ProductBase;
use api\models\V1\ProductBaseDescription;
use api\models\V1\ProductImage;
use Yii;
class ProductbaseController  extends Controller {
    public function actionIndex($data){
        if(!isset($data['display'])){
            $data['display']=[];
        }
        if(isset($data['content']) && $data['content'] ){
            $content=[];
            foreach($data['content'] as $value){
                $content=array_merge($content,$value);
            }
            $data['content']=$content;
        }else{
            $data['content']=[];
        }
        $this->getView()->registerCssFile("/assets/stylesheets/detail.css",['depends'=>[\api\assets\AppAsset::className()]]);
        $this->getView()->registerJsFile("/assets/script/base_detail.js",['depends'=>[\api\assets\AppAsset::className()]]);
        if(Yii::$app->request->get('product_base_code') && Yii::$app->request->get('shop_code')){
            $product_base_code=Yii::$app->request->get('product_base_code');
            $shop_code=Yii::$app->request->get('shop_code');
            $product_base = ProductBase::findOne(['product_base_code' => $product_base_code,'store_code'=>$shop_code]);
            $product=Product::find()->where(['product_base_code' => $product_base_code]);
            $data['data']['vip_price']=$product->min('vip_price')?$product->min('vip_price'):0;
            $data['data']['price']=$product->min('price')?$product->min('price'):0;
            $products=Product::find()->where(['product_base_id' => $product_base->product_base_id])->asArray()->all();
            $ids=[];
            foreach($products as $value){
                array_push($ids,$value['product_id']);
            }
            $promotion=PromotionDetail::find()->where(['product_id'=>$ids])->all();
            $data['data']['promotion']=[];
            if($promotion){
                foreach($promotion as $key=>$list){
                    $master=$list->promotion;
                    if($master->date_start<=date('Y-m-d H:i:s',time()) &&
                        $master->date_end>=date('Y-m-d H:i:s',time()) &&
                        $master->status==1){
                        $gifts=[];
                        if($list->gifts){
                            foreach($list->gifts as $gift){
                                $product=Product::findOne(['product_id'=>$gift->product_id]);
                                $gifts[]=[
                                    'name'=>ProductBaseDescription::findOne(['product_base_id'=>$product->product_base_id])->name,
                                    'sku_names'=>$this->getSkuNameByProductId($gift->product_id),
                                    'quantity'=>$gift->quantity,
                                    'price'=>$gift->price,
                                    'base_number'=>$gift->base_number,
                                    'url'=>Url::to(['/shop/detail','product_base_code'=>$product->product_base_code,'shop_code'=>$product->store_code])
                                ];
                            }
                        }
                        $sku_option=$this->getSkuNameByProductId($list->product_id);
                        $data['data']['promotion'][$key]=[
                            'title'=>"《".$master->name."》"."(截止：".date('Y-m-d',strtotime($master->date_end)).")",
                            'sku_names'=>$sku_option,
                            'amount'=>$list->begin_amount==0&&$list->end_amount==0? '':"满足（".$list->begin_amount.'~'.$list->end_amount.")金额",
                            'quantity'=>$list->begin_quantity==0 && $list->end_quantity==0 ? '':"满足（".$list->begin_quantity.'~'.$list->end_quantity.")数量",
                            'price_type'=>$list->pricetype,
                            'price'=>$list->price,
                            'rebate'=>$list->rebate,
                            'gifts'=>$gifts,
                        ];
                    }else{
                        continue;
                    }
                }
            }
            $sale_month=OrderProduct::find()->where(['product_base_id'=>$product_base->product_base_id])->innerJoinWith([
                'order'=>function ($query) {
                    $query->where(['and',['not in','order_status_id',[1,7,8,12]],['>','date_added',date('Y-m-d',time()-30*24*60*60)]]);
                }
            ]);
            $data['data']['sale_count']=$sale_month->sum('quantity')?$sale_month->sum('quantity'):0;
            $product_codes=Product::find('product_code')->where(['product_base_id'=>$product_base->product_base_id])->asArray()->all();
            if($product_base->bepresell){
                $data['data']['stock_count']=9999;
            }else{
               $stock= WarehouseStock::find()->where(['product_code'=>$product_codes]);
                $data['data']['stock_count']=$stock->sum('quantity')?$stock->sum('quantity'):0;
            }
            $data['data']['review_count']=Review::find()->where(['product_base_id'=>$product_base->product_base_id])->count('*');
            $description = ProductBaseDescription::findOne(['product_base_id' => $product_base->product_base_id]);
            $this->getView()->title=$description->name;
            $data['data']['description']=$description;
            $data['data']['images'] = ProductImage::find()->where(['product_base_id' => $product_base->product_base_id])->asArray()->all();
            if($product_base->spec_array){
                $cdata=explode(',',$product_base->spec_array);
                $vdata=[];
                foreach($cdata as $v){
                    $vdata[]=explode(';',$v);
                }
                $tmp_string = [];
                foreach($vdata as  $options_array){
                    foreach($options_array as $key => $op){
                        list($att_id,$att_value_id)=explode(':',$op);
                        $tmp_string[$att_id][$att_value_id]= $op;
                    }
                }
                $sku=[];
                foreach($tmp_string as $key=>$value){
                    $att_id=$key;
                    $content=[];
                    foreach($value as $k=>$v){
                        $att_value_id=$k;
                        $content[]=[
                            'id'=>$att_value_id,
                            'name'=>OptionValueDescription::findOne(['option_value_id'=>$att_value_id,'option_id'=>$att_id])->name,
                            'value'=>$v
                        ];
                    }
                    $sku[]=[
                        'id'=>$att_id,
                        'name'=>OptionDescription::findOne(['option_id'=>$att_id])->name,
                        'content'=>$content
                    ];
                }
                $product_base->spec_array=$sku;
            }else{
                $productpages=Product::find()->where(['product_base_id' => $product_base->product_base_id])->asArray()->all();
                $option_value_data=array();
                foreach($productpages as $value){
                    $option_value_name_str=$value['unit']?$value['unit']:"默认";
                    $option_value_name_str.=$value['format']?"(".$value['format'].")":"";
                    $option_value_data[]=array(
                        'id'=>$value['product_id'],
                        'name'=>$option_value_name_str,
                        'value'=>$value['product_id']
                    );
                }
                $product_option_data[]=array(
                    'id'=>0,
                    'name'=>'包装/规格',
                    'content'=>$option_value_data
                );
                $product_base->spec_array=$product_option_data;
            }
            $data['data']['base']=$product_base;

        $data['data']['sku_keys']=[];
        if($product_base->spec_array){
            foreach($product_base->spec_array as $key=>$value){
                if($value && $value['content']){
                    foreach($value['content'] as $v){
                        $data['data']['sku_keys'][$key]=isset($data['data']['sku_keys'][$key])?$data['data']['sku_keys'][$key]:[];
                        array_push($data['data']['sku_keys'][$key],$v['value']);
                    }
                }
            }
        }
        $data['data']['sku_data']=[];
        $sku_data=Product::find()->where(['product_base_id' => $product_base->product_base_id])->andWhere('sku !=""')->asArray()->all();
        if($sku_data){
            foreach($sku_data as $svalue){
                if($product_base->bepresell || $svalue['bepresell']){
                    $count=9999;
                }else{
                    $stock=WarehouseStock::findOne(['product_code'=>$svalue['product_code']]);
                    $count=$stock?$stock->quantity:0;
                }
                $data['data']['sku_data'][$svalue['sku']]=['price'=>$svalue['vip_price'],'sale_price'=>$svalue['price'],'count'=>$count];
            }

        }else{
            $data['data']['sku_data']=[];
            $sku_data=Product::find()->where(['product_base_id' => $product_base->product_base_id])->asArray()->all();
            if($sku_data){
                foreach($sku_data as $svalue){
                    if($product_base->bepresell || $svalue['bepresell']){
                        $count=9999;
                    }else{
                        $stock=WarehouseStock::findOne(['product_code'=>$svalue['product_code']]);
                        $count=$stock?$stock->quantity:0;
                    }
                    $data['data']['sku_data'][$svalue['product_id']]=['price'=>$svalue['vip_price'],'sale_price'=>$svalue['price'],'count'=>$count];
                }
            }

        }
        }
        return $data;
    }
    protected function getSkuNameByProductId($product_id){
        $sku_option=[];
        $product=Product::findOne(['product_id'=>$product_id]);
        if($product) {
            if ($product->sku) {
                $sku = explode(";", $product->sku);
                if ($sku) {
                    foreach ($sku as $sku_value) {
                        list($option_id, $option_value) = explode(':', $sku_value);
                        $option_name = OptionDescription::findOne(['option_id' => $option_id])->name;
                        $option_value_name = OptionValueDescription::findOne(['option_value_id' => $option_value])->name;
                        $sku_option[] = $option_value_name;
                    }
                }
            } else {
                $sku_option[] = $product->format ? $product->unit . "(" . $product->format . ")" : $product->unit;
            }
        }
        return $sku_option;
    }
    public function bindActionParams($action, $params){
        return $params;
    }
}