<?php

namespace api\modules\shop\controllers;

use yii\web\Controller;
use Yii;
class ModuleController extends Controller
{
    public function actionIndex($data)
    {
        if(in_array($data['moduleID'],['goodrecommend','daohang','dianzhao','servCenter','other','sidecataVert','productbase','productinfo','shopArchive','goodlist'])){
            if(isset($data['shop_code'])){
                $data['data']= isset($data['data'])?$data['data']:[];
                $data['data']=array_merge($data['data'],['shop_code'=>$data['shop_code']]);
            }
            $result= $this->run("/shop/".strtolower($data['moduleID'])."/index",['data'=> isset($data['data'])?$data['data']:[] ]);
            return $this->renderPartial($data['moduleID'].'/index',['data'=>$result]);
        }else{
            return $this->renderPartial($data['moduleID'].'/index',['data'=>isset($data['data'])?$data['data']:[]]);
        }

    }

    public function actionParams($data){
      if(file_exists(__DIR__."/../views/module/".$data['moduleID']."/module.php")){
          return require_once(__DIR__."/../views/module/".$data['moduleID']."/module.php");
      }else{
          return ;
      }
    }

    public function bindActionParams($action, $params){
        return $params;
    }
}
