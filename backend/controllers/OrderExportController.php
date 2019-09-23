<?php

namespace backend\controllers;

use api\models\V1\Coupon;
use api\models\V1\Order;
use api\models\V1\OrderDigitalProduct;
use api\models\V1\OrderSearch;
use api\models\V1\OrderPayment;
use api\models\V1\OrderShipping;
use api\models\V1\OrderProduct;
use api\models\V1\OrderProductGroup;
use api\models\V1\OrderHistory;
use api\models\V1\OrderStatus;
use api\models\V1\OrderTotal;
use api\models\V1\OrderType;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;

class OrderExportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionExport()
    {
		
		if(\Yii::$app->request->get("date_start") && \Yii::$app->request->get("date_start")){
				$date_start = \Yii::$app->request->get("date_start");
		}else{
			$date_start = date('Y-m-d',time());
		}
			$date_start = $date_start.' 00:00:00';
		if(\Yii::$app->request->get("date_end") && \Yii::$app->request->get("date_end")){
				$date_end = \Yii::$app->request->get("date_end");
		}else{
			$date_end = date('Y-m-d',time());
		}
			$date_end = $date_end.' 23:59:59';

		$order_status_o = OrderStatus::find()->all();
		foreach ($order_status_o as $key => $value) {
			$order_status[$value->order_status_id] = $value->name;
		}

        $order_types = OrderType::find()->all();
        if($order_types){
            foreach ($order_types as $value){
                $order_type[$value->code] = $value->name;
            }
        }else{
            $order_type = [
                'normal'=>'普通订单',
                'presell'=>'预售订单',
                'voucher'=>'礼品券',
                'restaurant'=>'订餐订单',
                'takeaway'=>'外卖订单',
                'recharge'=>'充值订单',
                'virtual'=>'虚拟订单',
                'ACTIVITY'=>'活动订单'
            ];
        }
        $orders = Order::find()->where(['>=','date_added',$date_start])->andWhere(['<=','date_added',$date_end])->andWhere(['sent_to_erp'=>'Y'])->all();
        $header = array(
	        '店铺编码'=>'string',
	        '店铺名称'=>'string',
          '订单时间'=>'datetime',
          '订单号'=>'string',
          '付款方式'=>'string',
          '网关交易流水号'=>'string',
          '订单类型'=>'string',
          '具体订单类型'=>'string',
          '订单状态'=>'string',
          '总计'=>'string',
          '商品金额'=>'string',
          '余额支付'=>'string',
          '折扣券名称'=>'string',
          '折扣券金额'=>'string',
          '运费'=>'string',
		  '其它优惠名称'=>'string',
	      '其它优惠金额'=>'string',
        );

		$writer = new XLSXWriter();
		$writer->writeSheetHeader('Sheet1', $header );
		foreach ($orders as $key => $value) {
			$order_type2 = '';
			if($value->order_type_code == 'recharge'){
				$dgp = OrderDigitalProduct::find()->where(['order_id'=>$value->order_id])->one();
				!is_null($dgp) ? $order_type2 = $dgp->model : $order_type2 = '';
			}

			$payment_rows = OrderPayment::find()->where(['order_id'=>$value->order_id])->all();
			$payment['balance'] = 0;
			$payment['method'] = '';
			if(!$payment_rows){
				$payment['method'] = $value->payment_method;
			}
			foreach ($payment_rows as $payment_row) {
				if($payment_row->payment_code == 'balance'){
					$payment['balance'] = $payment_row->total;
				}
				$payment_row->payment_code != 'balance' ? $payment['method'] = $payment_row->payment_method : $payment['method'] = '余额支付';
			}
			$product_rows = OrderProduct::find()->where(['order_id'=>$value->order_id])->all();
			$product_total = 0;
			foreach ($product_rows as $product_row) {
				$product_total = $product_total + $product_row->total;
			}
			$total = array();
			$total_rows = OrderTotal::find()->where(['order_id'=>$value->order_id])->all();
			$total['shipping'] = 0;
			$total['coupon'] = '';
			$total['coupon_name'] = '';
			$total['change_name'] = '';
			$total['change'] = 0;
			foreach ($total_rows as $total_row) {
				if($total_row->code == 'shipping'){ 
					$total['shipping'] = $total_row->value;
				}elseif($total_row->code == 'coupon'){
					$total['coupon'] += $total_row->value;
					$coupon = Coupon::find()->where(['coupon_id'=>$total_row->code_id])->one();
					$total['coupon_name']= $total['coupon_name']."|".($coupon?$coupon->name:'');
				}elseif(!in_array($total_row->code,['total','sub_total'])){
					$total['change_name'].=$total_row->title."( ".$total_row->value." )";
					$total['change']+=$total_row->value;
				}
			}
			$store=$value->store?$value->store:'';
			$writer->writeSheetRow('Sheet1', [
				($store?$store->store_code:''),
				($store?$store->name:''),
				$value->date_added,
				$value->order_id,
				$payment['method'],
				$value->payment_deal_no,
				$order_type[$value->order_type_code],
				$order_type2,
				$order_status[$value->order_status_id],
				$value->total,
				$product_total,
				$payment['balance'],
				$total['coupon_name'],
				$total['coupon'],
				$total['shipping'],
				$total['change_name'],
				$total['change'],
			]);
		}
	
		$writer->writeToFile('/tmp/output.xlsx');

		// 输入文件标签
		header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
		header("Content-Disposition: attachment; filename=output.xlsx");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		ob_clean();

		// 输出文件内容
		readfile('/tmp/output.xlsx');
    }

}
