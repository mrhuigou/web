<?php
namespace frontend\controllers;
use api\models\V1\OrderProduct;
use api\models\V1\Product;
use api\models\V1\Review;
use common\component\Track\Track;
use yii\web\Controller;
use api\models\V1\ProductBase;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class ProductController  extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60,
                'variations' => [
                    \Yii::$app->request->getAbsoluteUrl(),
                ]
            ]
        ];
    }


    public function actionIndex(){
        $store_code=\Yii::$app->request->get('store_code') ? \Yii::$app->request->get('store_code'): \Yii::$app->request->get('shop_code');
        $product_code=\Yii::$app->request->get('product_code') ? \Yii::$app->request->get('product_code'): \Yii::$app->request->get('product_base_code');
        $cur_sku='';
        if($store_code && $product_code){
            if($product=Product::findOne(['store_code'=>$store_code,'product_code'=>$product_code])){
                $product_base_id=$product->product_base_id;
                if($product->beintoinv==1){
                    $cur_sku=$product->sku;
                }
            }elseif($product=ProductBase::findOne(['product_base_code' => $product_code,'store_code'=>$store_code])){
                $product_base_id=$product->product_base_id;
            }else{
                $product_base_id=0;
            }
        }else{
            $product_base_id=\Yii::$app->request->get('product_base_id');
        }
        if($model = ProductBase::findOne(['product_base_id' => $product_base_id?$product_base_id:0])){
            Track::add($product_base_id,'view');
            return $this->render('index',['model'=>$model,'cur_sku'=>$cur_sku]);
        }else{
            throw new NotFoundHttpException("没有找到该商品！");
        }
    }
    public function actionReview($product_base_id=0){
        $data = Review::find()->andWhere(['product_base_id' => $product_base_id,'status'=>1]);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10','route'=>'/product/review']);
        $model = $data->orderBy('date_added desc')->offset($pages->offset)->limit($pages->limit)->all();
        return $this->renderAjax('review',[
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
                $query->where(['and',['=','sent_to_erp','Y'],['>','date_added',date('Y-m-d',time()-30*24*60*60)]]);
            }
        ])->orderBy('date_added desc');
        $min_price=$data?number_format($data->min('price'),2):0;
        $max_price=$data?number_format($data->max('price'),2):0;
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10','route'=>'/product/record']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->renderPartial('record',[
            'data'=>['price'=>$min_price<$max_price?$min_price.' - '.$max_price:$max_price],
            'model' => $model,
            'pages' => $pages,
        ]);

    }
}