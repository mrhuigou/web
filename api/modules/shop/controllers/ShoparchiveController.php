<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/11
 * Time: 15:48
 */

namespace api\modules\shop\controllers;
use api\models\V1\Store;
use yii\helpers\Url;
use yii\web\Controller;
class ShoparchiveController extends Controller {
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
        if(isset($data['shop_code'])){
            $store=Store::findOne(['store_code'=>$data['shop_code']]);
        }else{
            $store=Store::findOne(['store_code'=>\Yii::$app->request->get('shop_code')]);
        }

        if($store){
            $data['data']=[
                'store_name'=>$store->name,
                'store_code'=>$store->store_code,
                'store_id'=>$store->store_id,
                'url'=>Url::to(['/shop','shop_code'=>$store->store_code])
            ];
        }

        return $data;
    }
    public function bindActionParams($action, $params){
        return $params;
    }

}