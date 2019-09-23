<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/2/12
 * Time: 14:31
 */

namespace api\modules\shop\controllers;

use api\models\V1\CategoryStore;
use common\component\Helper\Helper;
use yii\web\Controller;
class DaohangController extends Controller {
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