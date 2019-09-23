<?php

namespace frontend\controllers;

use api\models\V1\CustomerCollect;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use common\component\SolrProductList\SolrProduct;
use console\models\Store;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

class CollectController extends \yii\web\Controller
{
    public $layout = 'main-user';
    
    public function actionIndex()
    {
    	if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }

        if (\Yii::$app->request->post()) {
        	$model = CustomerCollect::findOne(\Yii::$app->request->post('id'));
        	if($model && $model->customer_id = \Yii::$app->user->getId()){
        		$model->delete();
        		return json_encode('success');
        	}
        }
        if(\Yii::$app->request->get("type") && \Yii::$app->request->get("type") == "store" ){
            $type = 2;
        }else{
            $type = 1;
        }
        $model = new CustomerCollect();
            $dataProvider = new ActiveDataProvider([
                'query' => $model->find()->where(['customer_id'=>\Yii::$app->user->getId(),'type_id'=>$type])->orderBy('date_added DESC'),
                'pagination' => [
                    'pagesize' => '10',
                ]
            ]);


        return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);
    }
    public function actionAddCollect(){
        $product_id =  \Yii::$app->request->post("product_id");
        $store_id =  \Yii::$app->request->post("store_id");
        $type =  \Yii::$app->request->post("type");
        $json = array();
        if(\Yii::$app->user->isGuest){
            $json['success'] = '请您先登录';
        }else{
            if($type == 'product'){
                if(!empty($product_id) && !empty($store_id)){
                    $customer_collect = CustomerCollect::find()->where(['customer_id'=>\Yii::$app->user->getId(),'product_base_id'=>$product_id,'type_id'=>1])->all();
                    if(empty($customer_collect)) {
                        $product = ProductBase::findOne(['product_base_id'=>$product_id,'store_id'=>$store_id]);
                       if($product){
                           $new_customer_collect = new CustomerCollect();
                           $new_customer_collect->customer_id = \Yii::$app->user->getId();
                           $new_customer_collect->platform_id = '1';
                           $new_customer_collect->platform_code = 'PT0001';
                           $new_customer_collect->product_base_id = $product->product_base_id;
                           $new_customer_collect->product_base_code = $product->product_base_code;
                           $new_customer_collect->store_id = $product->store_id;
                           $new_customer_collect->store_code = $product->store_code;
                           $new_customer_collect->type_id = 1;
                           $new_customer_collect->date_added = date("Y-m-d H:i:s");
                           $new_customer_collect->save();
                       }
                        $json['success'] = '收藏成功';
                    }else{
                        $json['success'] = '已经收藏过了';
                    }
                }
            }elseif($type == 'store'){
                if(!empty($store_id)){
                    $customer_collect = CustomerCollect::find()->where(['customer_id'=>\Yii::$app->user->getId(),'store_id'=>$store_id,'type_id'=>2])->all();
                    if(empty($customer_collect)) {
                        $store = Store::findOne(['store_id'=>$store_id]);
                        if($store){
                            $new_customer_collect = new CustomerCollect();
                            $new_customer_collect->customer_id = \Yii::$app->user->getId();
                            $new_customer_collect->platform_id = '1';
                            $new_customer_collect->platform_code = 'PT0001';
                            $new_customer_collect->store_id = $store->store_id;
                            $new_customer_collect->store_code = $store->store_code;
                            $new_customer_collect->type_id = 2;
                            $new_customer_collect->date_added = date("Y-m-d H:i:s");
                            $new_customer_collect->save();
                        }
                        $json['success'] = '收藏成功';
                    }else{
                        $json['success'] = '已经收藏过了';
                    }
                }
            }
        }

        return Json::encode($json);exit;

    }

}
