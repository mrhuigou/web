<?php

namespace backend\controllers;

use api\models\V1\CategoryDescription;
use api\models\V1\Manufacturer;
use api\models\V1\ProductBaseDescription;
use backend\models\ReportOrderCustomerSearch;
use backend\models\ReportOrderProductSearch;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
/**
 * RefundController implements the CRUD actions for ReturnBase model.
 */
class ReportOrderProductController extends Controller
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

		$searchModel = new ReportOrderProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	public function actionAutoComplete(){
		$data=[];
		if($query=Yii::$app->request->get('term')){
			if($product_data=ProductBaseDescription::find()->where(['like','name',$query])->orWhere(['like','product_base_code',$query])->orderBy('product_base_id asc')->limit(10)->all()){
				foreach($product_data as $value){
					$data[]=[
                        'value'=>"product|".$value->product_base_code,
						'label'=>$value->name."|".$value->product_base_code,
					];
				}
			}
            if($cate_data=CategoryDescription::find()->where(['like','name',$query])->orWhere(['like','code',$query])->orderBy('category_id asc')->limit(10)->all()){
                foreach($cate_data as $value){
                    $data[]=[
                        'value'=>"category|".$value->code,
                        'label'=>$value->code."---".$value->name."---分类",
                    ];
                }
            }
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	public function actionExport(){
		$searchModel = new ReportOrderProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setPagination(['pagesize'=>$dataProvider->totalCount]);
		$header = array(
			'product_code'=>'string',
			'name'=>'string',
			'quantity_total'=>'string',
			'product_total'=>'string',
			'pay_total'=>'string',
			'order_count'=>'string',
            'stock'=>'string',
            'category_name' => 'string'
		);
		$writer = new XLSXWriter();
		$writer->writeSheetHeader('Sheet1', $header );
		if($model=$dataProvider->getModels()){
			foreach($model as $value){
				$writer->writeSheetRow('Sheet1',array_values($value));
			}
		}
		$file="/tmp/output_".date('YmdHis').".xlsx";
		$writer->writeToFile($file);
		// 输入文件标签
		header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
		header("Content-Disposition: attachment; filename=output_".date('YmdHis').".xlsx");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		ob_clean();
		// 输出文件内容
		readfile($file);

	}
}