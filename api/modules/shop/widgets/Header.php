<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/19
 * Time: 15:04
 */
namespace api\modules\shop\widgets;
use api\models\V1\Store;
use yii\base\Widget;
class Header extends Widget{
    public function init()
    {
        parent::init();

    }
    public function run(){
        parent::run();
        $data=[];
        if($store_code=\Yii::$app->request->get('shop_code')){
            $data['store']=Store::findOne(['store_code'=>$store_code]);
        }
        if($keyword=\Yii::$app->request->get('keyword')){
            $data['keyword']=$keyword;
        }else{
            $data['keyword']='';
        }
        // create a client instance
       // $client = \Yii::$app->solr;

      return $this->render('/widgets/header', ['data' => $data]);
    }
}