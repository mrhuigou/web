<?php

namespace console\controllers\v1;
use Yii;
use yii\helpers\Json;
use common\component\curl;
use common\models\Country;
use common\models\Province;
use common\models\City;
use common\models\District;

class ZoneController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getPlaceForJson');
            $content = $client->getInterfaceForJson($data);
            $result=Json::decode($content);
            $this->autoCountryInsert($result['data']);
            $data=$this->CreatRequestParams('getProvinceForJson');
            $content = $client->getInterfaceForJson($data);
            $result=Json::decode($content);
            $this->autoProvinceInsert($result['data']);
            $data=$this->CreatRequestParams('getCityForJson');
            $content = $client->getInterfaceForJson($data);
            $result=Json::decode($content);
            $this->autoCityInsert($result['data']);
            $data=$this->CreatRequestParams('getDistrictForJson');
            $content = $client->getInterfaceForJson($data);
            $result=Json::decode($content);
            $this->autoDistrictInsert($result['data']);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function autoCountryInsert($datas){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                if(!$model=Country::findOne(['code'=>$data['CODE']])){
                    $model=new Country();
                }
                $model->code=$data['CODE'];
                $model->name=$data['NAME'];
                $model->status=$data['STATUS']=='ACTIVE'?1:0;
                if (!$model->save()) {
                    throw new \Exception();
                }
            }
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
        }
    }
    protected function autoProvinceInsert($datas){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                if(!$model=Province::findOne(['code'=>$data['CODE']])){
                    $model=new Province();
                }
                $model->country_id=Country::findOne(['code'=>$data['COUNTYE']])->id;
                $model->code=$data['CODE'];
                $model->name=$data['NAME'];
                $model->status=$data['STATUS']=='ACTIVE'?1:0;
                if (!$model->save()) {
                    throw new \Exception();
                }
            }
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
        }
    }

    protected function autoCityInsert($datas){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                if(!$model=City::findOne(['code'=>$data['CODE']])){
                    $model=new City();
                }
                $model->province_id=Province::findOne(['code'=>$data['PROVINCE']])->id;
                $model->code=$data['CODE'];
                $model->name=$data['NAME'];
                $model->status=$data['STATUS']=='ACTIVE'?1:0;
                if (!$model->save()) {
                    throw new \Exception();
                }
            }
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
        }
    }
    protected function autoDistrictInsert($datas){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                if(!$model=District::findOne(['code'=>$data['CODE']])){
                    $model=new District();
                }
                $model->city_id=City::findOne(['code'=>$data['CITY']])->id;
                $model->code=$data['CODE'];
                $model->name=$data['NAME'];
                $model->status=$data['STATUS']=='ACTIVE'?1:0;
                if (!$model->save()) {
                    throw new \Exception();
                }
            }
            $transaction->commit();
        } catch(\Exception $e){
            $transaction->rollBack();
        }
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
