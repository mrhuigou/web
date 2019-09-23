<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/2/25
 * Time: 11:24
 */

namespace api\modules\shop\controllers;
use yii\web\Controller;

class ServcenterController  extends Controller {
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
        return $data;
    }
    public function bindActionParams($action, $params){
        return $params;
    }
}