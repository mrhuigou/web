<?php

namespace h5\controllers;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use common\component\Track\Track;
use yii\web\NotFoundHttpException;

class ProductController extends \yii\web\Controller
{
    public function actionIndex()
    {

	if (\Yii::$app->user->isGuest) {
	return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
	}

        $store_code=\Yii::$app->request->get('store_code') ? \Yii::$app->request->get('store_code'): \Yii::$app->request->get('shop_code');
        $product_code=\Yii::$app->request->get('product_code') ? \Yii::$app->request->get('product_code'): \Yii::$app->request->get('product_base_code');
        $cur_sku='';
        $product = '';
        if($store_code && $product_code){
            if($product=Product::findOne(['store_code'=>$store_code,'product_code'=>$product_code])){
                $product_base_id=$product->product_base_id;
                if($product->beintoinv==1){
                    $cur_sku=$product->sku;
                }else{
                    $product = Product::findOne(['store_code'=>$store_code,'product_base_id'=>$product_base_id,'beintoinv'=>1]);
                    if($product->beintoinv == 1){
                        $cur_sku=$product->sku;
                    }else{
                        throw new NotFoundHttpException("该商品已下架！");
                    }

                }
            }else{
                $product_base_id=0;
            }
        }else{
            $product_base_id=\Yii::$app->request->get('product_base_id');
        }
        if( $model = ProductBase::findOne(['product_base_id' => $product_base_id?$product_base_id:0])){
            Track::add($product_base_id,'view');
            return $this->render('index',['model'=>$model,'cur_sku'=>$cur_sku,'product'=>$product]);
        }else{
            throw new NotFoundHttpException("没有找到该商品！");
        }
    }
    public function actionSku(){
        $product_base_id=\Yii::$app->request->get('id');
        $product_base=ProductBase::findOne(['product_base_id'=>$product_base_id]);
        Track::add($product_base_id,'search_click');
        return $this->renderPartial('sku',['product_base'=>$product_base]);
    }
    public function actionTestProduct(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
        }

        $store_code=\Yii::$app->request->get('store_code') ? \Yii::$app->request->get('store_code'): \Yii::$app->request->get('shop_code');
        $product_code=\Yii::$app->request->get('product_code') ? \Yii::$app->request->get('product_code'): \Yii::$app->request->get('product_base_code');
        $cur_sku='';
        if($store_code && $product_code){
            if($product=Product::findOne(['store_code'=>$store_code,'product_code'=>$product_code])){
                $product_base_id=$product->product_base_id;
                if($product->beintoinv==1){
                    $cur_sku=$product->sku;
                }
            }else{
                $product_base_id=0;
            }
        }else{
            $product_base_id=\Yii::$app->request->get('product_base_id');
        }
        if( $model = ProductBase::findOne(['product_base_id' => $product_base_id?$product_base_id:0])){
            Track::add($product_base_id,'view');
            return $this->render('test_view',['model'=>$model,'cur_sku'=>$cur_sku]);
        }else{
            throw new NotFoundHttpException("没有找到该商品！");
        }
    }
}
