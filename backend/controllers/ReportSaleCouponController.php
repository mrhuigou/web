<?php

namespace backend\controllers;

use api\models\V1\Coupon;
use api\models\V1\ReportSaleCouponSearch;
use common\extensions\widgets\xlsxwriter\XLSXWriter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;



class ReportSaleCouponController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex(){

        $searchModel = new ReportSaleCouponSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionExport(){
        $type = Yii::$app->request->get('type');
        if($type == 'export_use_customer' || $type == 'export_not_use_customer'){ //导出使用某折扣券使用客户的信息
            $searchModel = new ReportSaleCouponSearch();
            $customers = $searchModel->export(Yii::$app->request->queryParams);
            if($customers){
                $header = array(
                    '用户ID'=>'string',
                    '电话号码'=>'string',
                );
                $writer = new XLSXWriter();
                $writer->writeSheetHeader('Sheet1', $header );

                foreach ($customers as $customer){
                    $writer->writeSheetRow('Sheet1', [
                        ($customer?$customer->customer_id:''),
                        ($customer?$customer->telephone:''),
                    ]);
                }
            }
        }

        $writer->writeToFile('output.xlsx');

        // 输入文件标签
        header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
        header("Content-Disposition: attachment; filename=output.xlsx");  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        ob_clean();

        // 输出文件内容
        readfile('output.xlsx');
    }
	public function actionCouponAutoComplete(){
		$data=[];
		$coupon_model=[
			'OTHER'=>'其它',
			'ORDER'=>'订单券',
			'BUY_GIFTS'=>'买赠券',
			'BRAND'=>'品牌券',
			'CLASSIFY'=>'分类类',
			'PRODUCT'=>'商品券'
		];
		if($query=Yii::$app->request->get('term')){
			if($filer_datas=Coupon::find()->where(['like','name',$query])->orWhere(['like','code',$query])->andWhere(['status'=>1])->limit(10)->all()){
				foreach ($filer_datas as $value){
					$data[]=[
						'value'=>$value->code,
						'label'=>"[".$value->code."]---".$value->name."---".$coupon_model[$value->model?$value->model:"OTHER"],
					];
				}
			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
}