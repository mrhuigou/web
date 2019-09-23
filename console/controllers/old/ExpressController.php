<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/8
 * Time: 13:54
 */
namespace console\controllers\old;
use api\models\V1\ExpressOrder;
use api\models\V1\ExpressStatus;
use yii\base\ErrorException;
use yii\helpers\Json;

class ExpressController extends \yii\console\Controller{
	//获取结果数据方法
	protected function getResult($data)
	{
		$result = Json::decode($data, true);
		return $result;
	}

	//生成请求数据方法
	protected function CreatRequestParams($a, $d = [], $v = '1.0')
	{
		$t = time();
		$m = 'webservice';
		$key = 'asdf';
		$data = ['a' => $a, 'c' => 'NONE', 'd' => $d, 'f' => 'json', 'k' => md5($t . $m . $key), 'm' => $m, 'l' => 'CN', 'p' => 'soap', 't' => $t, 'v' => $v];
		return Json::encode($data);
	}
	public function actionOrder(){
		try{
			$input_data=[];
			$orders=ExpressOrder::find()->where(['send_status'=>0])->orderBy('id asc')->limit(100)->all();
			if($orders){
				foreach ($orders as $order){
					$value=[
						'COMPANY_CODE'=>$order->company?$order->company->legal_no:'',
						'ORDER_CODE'=>'OE01'.$order->id,
						'CUSTOMER_CODE'=>$order->customer_id,
						'ORDER_TYPE'=>$order->order_type,
						'CONTACT_NAME'=>$order->contact_name,
						'TELEPHONE'=>$order->telephone,
						'CITY'=>$order->city,
						'ADDRESS'=>$order->district.$order->address,
						'DESCRIPTION'=>$order->remark,
						'WEIGHT'=>'',
						'VOLUME'=>'',
						'DELIVERY_TYPE'=>$order->delivery_type,
						'DELIVERY_DATE'=>$order->delivery_date,
						'DELIVERY_TIME'=>$order->delivery_time,
						'BOX_QUANTITY'=>'',
						'AMOUNT'=>$order->total,
						'DEPOT_TYPE'=>'NORMAL',
						'ORDER_DATE'=>date('Y-m-d H:i:s',$order->create_at)
					];
					$remark=[];
					if($order->expressOrderProducts){
						foreach ($order->expressOrderProducts as $key=>$product){
						    if(!$product->product_base_code){
                                $remark[]=$product->product_name?$product->product_name:$product->description;
                            }
							$value['DETAILS'][]=[
								'LINENO'=>$key+1,
								'PRODUCT_CODE'=>$product->product_base_code,
								'PUCODE'=>$product->product_code,
								'SHOP_CODE'=>$product->shop_code,
								'QUANTITY'=>$product->quantity,
								'DESCRIPTION'=>$product->product_name?$product->product_name:$product->description
							];
						}
					}
					if($remark){
					    $value['DESCRIPTION']="[".implode("][",$remark)."]---".$value['DESCRIPTION'];
                    }

					$input_data[]=$value;
				}
			}

			if ($input_data) {
				$client = new \SoapClient(\Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
				foreach ($input_data as $val) {
					$data = $this->CreatRequestParams('createExpress', [$val]);
					$content = $client->getInterfaceForJson($data);
					$result = $this->getResult($content);
					if (isset($result['status']) && $result['status'] == 'OK') {
                        $id =  substr($val['ORDER_CODE'], 4);
						$model = ExpressOrder::findOne(['id' => $id]);
						if ($model) {
							$model->send_status = 1;
							$model->send_time = time();
							$model->save();
						}
					}else{
						echo $data."\r\n";
						print_r($result);
					}
				}
			}
		}catch (ErrorException $e){
          echo $e->getMessage();
		}
		echo "run time" . date("Y-m-d H:i:s", time());
	}
	public function actionStatus(){
		$client = new \SoapClient(\Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
		try {
			$data = $this->CreatRequestParams('getExpressStatusForJson');
			$content = $client->getInterfaceForJson($data);
			if (is_soap_fault($content)) {
				throw new \Exception("can not soap url");
			}
			$result = Json::decode($content);
			if (isset($result['data']) && $result['data']) {
				$this->OrderStatus($result['data']);
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
		echo "run time" . date("Y-m-d H:i:s", time());
	}
	public function OrderStatus($datas){
		$transaction = \Yii::$app->db->beginTransaction();
		try {
			foreach ($datas as $data) {
				$order_id=trim($data['CODE'], 'OE01');
				$express_status=ExpressStatus::findOne(['code'=>trim($data['STATUS'])]);
				$express_status_id=$express_status?$express_status->id:0;
				if($order=ExpressOrder::findOne(['id'=>$order_id])){
					if($order->express_status_id!==$express_status_id){
						$order->express_status_id=$express_status_id;
						$order->update_at=time();
						$order->save();
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw new \Exception($e->getMessage());
		}
	}
}