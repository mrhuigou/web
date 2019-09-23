<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/9
 * Time: 13:53
 */

namespace api\modules\shop\controllers;
use api\models\V1\AttributeDescription;
use api\models\V1\OrderProduct;
use api\models\V1\ProductBase;
use api\models\V1\ProductBaseAttribute;
use api\models\V1\Review;
use yii\data\Pagination;
use yii\web\Controller;
use api\models\V1\ProductBaseDescription;
use Yii;
class ProductinfoController  extends Controller {
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
        if(Yii::$app->request->get('product_base_code') && Yii::$app->request->get('shop_code')){
            $product_base_code=Yii::$app->request->get('product_base_code');
            $shop_code=Yii::$app->request->get('shop_code');
            $product_base = ProductBase::findOne(['product_base_code' => $product_base_code,'store_code'=>$shop_code]);
            $data['data']['base']=$product_base;
            $data['data']['description'] = ProductBaseDescription::findOne(['product_base_id' => $product_base->product_base_id]);
            $sale_month=OrderProduct::find()->where(['product_base_id'=>$product_base->product_base_id])->innerJoinWith([
                'order'=>function ($query) {
                    $query->where(['and',['not in','order_status_id',[1,7,8,12]],['>','date_added',date('Y-m-d',time()-30*24*60*60)]]);
                }
            ]);
            $data['data']['sale_count']=$sale_month->sum('quantity')?$sale_month->sum('quantity'):0;
            $data['data']['review_count']=Review::find()->where(['product_base_id'=>$product_base->product_base_id])->count('*');
            $data['data']['reviews']=$this->actionReview($product_base->product_base_code);
            $data['data']['records']=$this->actionRecord($product_base->product_base_code);
            $product_attributes=ProductBaseAttribute::find()->where(['product_base_id'=>$product_base->product_base_id])->asArray()->all();
            if($product_attributes){
                foreach($product_attributes as $key=>$value){
                    $attri_des=AttributeDescription::findOne(['attribute_id'=>$value['attribute_id']]);
                    $product_attributes[$key]['name']=$attri_des?$attri_des->name:"***";
                }
            }
            $data['data']['attributes']=$product_attributes;
        }
        return $data;
    }
    public function actionReview($product_base_code=0){
        if($product_base_code==0){
            $product_base_code=Yii::$app->request->get('product_base_code');
        }
        $data = Review::find()->andWhere(['product_base_code' => $product_base_code])->orderBy('date_added desc');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10','route'=>'/shop/productinfo/review']);
       $model = $data->offset($pages->offset)->limit($pages->limit)->all();

       return $this->renderPartial('/module/productinfo/review',[
           'model' => $model,
           'pages' => $pages,
       ]);

    }
    public function actionRecord($product_base_code=0){
        if($product_base_code==0){
            $product_base_code=Yii::$app->request->get('product_base_code');
        }
        $data = OrderProduct::find()->andWhere(['product_base_code' => $product_base_code])->innerJoinWith([
            'order'=>function ($query) {
                $query->where(['and',['not in','order_status_id',[1,7,8,12]],['>','date_added',date('Y-m-d',time()-30*24*60*60)]]);
            }
        ])->orderBy('date_added desc');
        $min_price=$data?number_format($data->min('price'),2):0;
        $max_price=$data?number_format($data->max('price'),2):0;
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10','route'=>'/shop/productinfo/record']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->renderPartial('/module/productinfo/record',[
            'data'=>['price'=>$min_price<$max_price?$min_price.' - '.$max_price:$max_price],
            'model' => $model,
            'pages' => $pages,
        ]);

    }
    public function bindActionParams($action, $params){
        return $params;
    }
}