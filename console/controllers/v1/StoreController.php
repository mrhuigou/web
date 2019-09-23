<?php

namespace console\controllers\v1;
use common\models\BusinessZone;
use common\models\City;
use common\models\District;
use common\models\LegalPerson;
use common\models\Market;
use Yii;
use yii\helpers\Json;
use common\component\curl;
use common\models\Store;
class StoreController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getShop');
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
                if(!$model=Store::findOne(['code'=>$data['CODE']])){
                    $model=new Store();
                    $model->creat_datetime=$data['UPDATETIME'];
                }
                $model->code=$data['CODE'];
                $model->name=$data['NAME'];
                $model->title=$data['EXTENDTITLE'];
                $model->logo=$data['PICTUREURL'];
                $model->market_id=Market::findOne(['code'=>$data['MARKETCODE']])->id;
                $model->legal_person_id=LegalPerson::findOne(['code'=>$data['COMPANYCODE']])->id;
                $model->business_zone_id=BusinessZone::findOne(['code'=>$data['BUSINESSZONECODE']])->id;
                $model->province_id=City::findOne(['code'=>$data['CITYCODE']])->province_id;
                $model->city_id=City::findOne(['code'=>$data['CITYCODE']])->id;
                $model->district_id=District::findOne(['code'=>$data['DISTRICTCODE']])->id;
                $model->address=$data['ADDRESS'];
                $model->telephone=$data['TELEPHONE'];
                $model->latitude=$data['LATITUDE'];
                $model->longitude=$data['LONGITUDE'];
                $model->status=$data['STATUS']=='ACTIVE'?1:0;
                $model->update_datetime=$data['UPDATETIME'];
                if (!$model->save()) {
                    throw new \Exception();
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
