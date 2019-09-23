<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/2/7
 * Time: 10:43
 */

namespace console\controllers\old;
use api\models\V1\Warehouse;
use api\models\V1\WarehouseLog;
use api\models\V1\WarehouseStock;
use api\models\V1\WarehouseStockSchedule;
use yii\helpers\Json;
use yii\console\Exception;
use Yii;
class StockController extends \yii\console\Controller
{
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
    //同步库存信息
    public function actionAuto(){
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('autoStock');
            $content = $client->getInterfaceForJson($data);
            if (is_soap_fault($content)) {
                throw new \Exception("can not soap url");
            }
            $result=Json::decode($content);
            $this->Autostock($result['data']);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        echo "run time".date("Y-m-d H:i:s",time());
    }
    //同步订单状态数据方法
    public function actionAutoschedule(){
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('autoScheduleStockForJson');
            $content = $client->getInterfaceForJson($data);
            if (is_soap_fault($content)) {
                throw new \Exception();
            }
            $result=Json::decode($content);
            if(isset($result['data'])&& $result['data']){
               $this->Autostockschedule($result['data']);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        echo "run time".date("Y-m-d H:i:s",time());
    }
    //同步库存信息
    public function Autostock($datas){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                $warehouse=Warehouse::findOne(['warehouse_code'=>$data['CODE']]);
                if(!$warehouse){
                    continue;
                }
                if(!$model=WarehouseStock::findOne(['warehouse_id'=>$warehouse->warehouse_id,'product_code'=>$data['PUCODE']])){
                    $model=new WarehouseStock();
                }
                $model->warehouse_id=$warehouse->warehouse_id;
                $model->product_code=$data['PUCODE'];
                $model->quantity=intval($data['QUANTITY']);
                $model->cycle_period=$data['CYCLEPERIOD'];
                $model->product_date=isset($data['PRODUCEDATE'])?$data['PRODUCEDATE']:"";
                if (!$model->save()) {
                    throw new \Exception(json_encode($model->errors));
                }
//                $log=new WarehouseLog();
//                $log->type='wms';
//                $log->product_code=$data['PUCODE'];
//                $log->qty=intval($data['QUANTITY']);
//                $log->create_time=time();
//                $log->save();
            }
            $transaction->commit();
        } catch(Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    //同步周期库存信息
    public function Autostockschedule($datas){
        $transaction=\Yii::$app->db->beginTransaction();
        try{
            foreach($datas as $data){
                $warehouse=Warehouse::findOne(['warehouse_code'=>$data['CODE']]);
                if(!$warehouse){
                    continue;
                }
                if(!$model=WarehouseStockSchedule::findOne(['warehouse_id'=>$warehouse->warehouse_id,'product_code'=>$data['PUCODE']])){
                    $model=new WarehouseStockSchedule();
                }
                $model->warehouse_id=$warehouse->warehouse_id;
                $model->product_code=$data['PUCODE'];
                $model->quantity=intval($data['QUANTITY']);
                $model->schedule_date=$data['DATE'];
                if (!$model->save()) {
                    throw new \Exception(json_encode($model->errors));
                }
            }
            $transaction->commit();
        } catch(Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}