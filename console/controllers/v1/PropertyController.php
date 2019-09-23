<?php

namespace console\controllers\v1;
use Yii;
use yii\helpers\Json;
use common\component\curl;
use common\models\PropertyGroup;
use common\models\Property;
class PropertyController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getAttributeGroupForJson');
            $content = $client->getInterfaceForJson($data);
            if (is_soap_fault($content)) {
                throw new \Exception();
            }
            $result=Json::decode($content);
            $this->autoInsert($result['data']);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function autoInsert($datas){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                if(!$model=PropertyGroup::findOne(['code'=>$data['CODE']])){
                    $model=new PropertyGroup();
                }
                $model->code=$data['CODE'];
                $model->name=$data['NAME'];
                $model->status=1;
                if (!$model->save()) {
                    throw new \Exception();
                }
                if(isset($data['ATTRIBUTES']) && $data['ATTRIBUTES'] ) {
                    $transaction=\Yii::$app->db->beginTransaction();
                    try{
                        foreach($data['ATTRIBUTES'] as $value){
                            if(!$m=Property::findOne(['code'=>$value['CODE'],'property_group_id'=>$model->id])){
                                $m=new Property();
                            }
                            $m->property_group_id=$model->id;
                            $m->code=$value['CODE'];
                            $m->name=$value['NAME'];
                            $m->is_search=$value['ISSEARCH']?1:0;
                            $m->status=1;
                            if (!$m->save()) {
                                throw new \Exception();
                            }
                        }
                        $transaction->commit();
                    }catch (\Exception $e){
                        $transaction->rollBack();
                    }
                }
            }
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
        }
    }
    protected function genTree($items,$id='id',$pid='pid',$son = 'children'){
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
