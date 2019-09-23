<?php

namespace console\controllers\v1;
use Yii;
use yii\helpers\Json;
use common\component\curl;
use common\models\Brand;
class BrandController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getBrandForJson');
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

    protected function autoInsert($datas,$parent_id=0){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                if(!$model=Brand::findOne(['code'=>$data['CODE']])){
                    $model=new Brand();
                }
                $model->code=$data['CODE'];
                $model->name=$data['NAME'];
                $model->parent_id=$parent_id;
                $model->description=$data['DESCRIPTION'];
                $model->image=$data['IMAGEURL'];
                $model->status=$data['STATUS']=='ACTIVE'?1:0;
                $model->date_added=$data['UPDATETIME'];
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
