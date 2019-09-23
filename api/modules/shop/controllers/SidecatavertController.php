<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/2/25
 * Time: 11:24
 */

namespace api\modules\shop\controllers;
use common\component\Helper\Helper;
use yii\helpers\Url;
use yii\web\Controller;

class SidecatavertController  extends Controller {
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
        if(isset($data['display']['shop_data']) && $data['display']['shop_data'] ){
            $store_code=\Yii::$app->request->get('shop_code');
            foreach($data['display']['shop_data'] as $key=>$value){
                $data['display']['shop_data'][$key]['url']=Url::to(['/shop/category','shop_code'=>$store_code,'cat_id'=>$value['code']]);
            }
            $data['display']['shop_data']= Helper::genTree($data['display']['shop_data'],'code','parentCode');
        }
        return $data;
    }
    public function bindActionParams($action, $params){
        return $params;
    }
}