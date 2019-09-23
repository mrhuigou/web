<?php

namespace console\controllers\v1;
use Yii;
use yii\helpers\Json;
use common\component\curl;
use common\models\Category;
use common\models\PropertyGroup;
use common\models\Industory;
class CategoryController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getProductTypeForJson');
            $content = $client->getInterfaceForJson($data);
            if (is_soap_fault($content)) {
                throw new \Exception();
            }
            $result=Json::decode($content);
            $datas=$this->genTree($result['data'],'CODE','PARENTCODE','children');
            $this->autoInsert($datas);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    function autoInsert($datas,$parent_id=0){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                if(!$model=Category::findOne(['code'=>$data['CODE']])){
                    $model=new Category();
                }
                    $model->code=$data['CODE'];
                    $model->name=$data['NAME'];
                    $model->parent_id=$parent_id;
                    $model->sort_order=0;
                    $model->property_group_id=PropertyGroup::findOne(['code'=>$data['ATTRIBUTEGROUPCODE']])->id;
                    $model->industory_id=Industory::findOne(['code'=>$data['INDUSTORYCODE']])->id;
                    $model->status=1;
                    if (!$model->save()) {
                        throw new \Exception();
                    }
                if(isset($data['children'])) {
                    $this->autoInsert($data['children'],$model->id);
                }
            }
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
        }
    }
    function genTree($items,$id='id',$pid='pid',$son = 'children'){
        $tree = array(); //格式化的树
        $tmpMap = array();  //临时扁平数据
        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }
        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        unset($tmpMap);
        return $tree;
    }

    //获取结果数据方法
    protected function getResult($data){
        $result=Json::decode($data,true);
        return $result;
    }

    //生成请求数据方法
    protected function CreatRequestParams($a,$d=array(),$v='1.0'){
        $t=time();
        $m='webservice';
        $key='asdf';
        $data=array('a'=>$a,'c'=>'NONE','d'=>$d,'f'=>'json','k'=>md5($t.$m.$key),'m'=>$m,'l'=>'CN','p'=>'soap','t'=>$t,'v'=>$v);
        return Json::encode($data);
    }
}
