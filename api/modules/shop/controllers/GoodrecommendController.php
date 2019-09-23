<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/2/12
 * Time: 14:31
 */

namespace api\modules\shop\controllers;
use api\models\V1\Product;
use common\component\image\Image;
use yii\helpers\Url;
use yii\web\Controller;
use api\models\V1\ProductBase;
use api\models\V1\ProductBaseDescription;
class GoodrecommendController extends Controller {
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
        //格式化产品数据
        if(isset($data['content']['product_list']) && $data['content']['product_list']){
            $product_datas=[];
            foreach($data['content']['product_list'] as $key=>$product){
                $product_base=ProductBase::findOne(['product_base_code'=>$product['productCode']]);
                if($product_base){
                    $description=ProductBaseDescription::findOne(['product_base_id'=>$product_base->product_base_id]);
                    $product_datas[$key]=[
                        'product_base_code'=>$product_base->product_base_code,
                        'store_code'=>$product_base->store_code,
                        'product_name'=>$description->name,
                        'product_image'=>Image::resize($product_base->image,242,242),
                        'product_sale_price'=>Product::findOne(['product_base_id'=>$product_base->product_base_id])->price,
                        'product_vip_price'=>Product::findOne(['product_base_id'=>$product_base->product_base_id])->vip_price,
                        'href'=>Url::to(['/shop/detail','shop_code'=>$product_base->store_code,'product_base_code'=>$product_base->product_base_code],true),
                    ];
                }
            }
            $data['content']['product_list']=$product_datas;
        }
        return $data;
    }
    public function bindActionParams($action, $params){
        return $params;
    }
}