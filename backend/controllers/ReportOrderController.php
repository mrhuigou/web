<?php

namespace backend\controllers;

use api\models\V1\ReportOrderSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;
/**
 * RefundController implements the CRUD actions for ReturnBase model.
 */
class ReportOrderController extends Controller
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

        $searchModel = new ReportOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      //  print_r($dataProvider->getModels());exit;
        $datas_all = $dataProvider->getModels();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'datas_all' =>$datas_all
        ]);

    }
	public function actionExport(){
		$searchModel = new ReportOrderSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setPagination(['pagesize'=>$dataProvider->totalCount]);
		$header = array(
			'日期'=>'string',
			'下单用户数'=>'string',
			'订单总数'=>'string',
			'商品订单数'=>'string',
			'充值订单数'=>'string',
			'订单总额'=>'string',
			'商品销售额'=>'string',
            '首次下单用户数'=>'string',
            '注册并下单数'=>'string',
		);
		$writer = new XLSXWriter();
		$writer->writeSheetHeader('Sheet1', $header );
		if($model=$dataProvider->getModels()){
			foreach($model as $value){
				$writer->writeSheetRow('Sheet1',[
					$value['date'],$value['customer_count'],$value['orders'],$value['orders']-$value['recharge_count'],$value['recharge_count'],$value['total'],$value['sale_total'],$value['firt_order_count'],$value['sign_date_count']
				]);
			}
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