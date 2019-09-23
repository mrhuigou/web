<?php

namespace backend\controllers;

use api\models\V1\CategoryDescription;
use api\models\V1\Manufacturer;
use api\models\V1\ProductBaseDescription;
use backend\models\ReportOrderCustomerSearch;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
/**
 * RefundController implements the CRUD actions for ReturnBase model.
 */
class ReportOrderCustomerController extends Controller
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

        $searchModel = new ReportOrderCustomerSearch();
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
							'label'=>$value->product_base_code."---".$value->name."---商品",
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
			if($brand_data=Manufacturer::find()->where(['like','name',$query])->orWhere(['like','code',$query])->orderBy('manufacturer_id asc')->limit(10)->all()){
				foreach($brand_data as $value){
					$data[]=[
						'value'=>"brand|".$value->code,
						'label'=>$value->code."---".$value->name."---品牌",
					];
				}
			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	public function actionExport(){
		$searchModel = new ReportOrderCustomerSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setPagination(['pagesize'=>$dataProvider->totalCount]);
		$header = array(
			'用户ID'=>'string',
			'电话'=>'string',
			'下单次数'=>'string',
			'最后购物时间'=>'string',
		);
		$writer = new XLSXWriter();
		$writer->writeSheetHeader('Sheet1', $header );
		if($model=$dataProvider->getModels()){
			foreach($model as $value){
				$writer->writeSheetRow('Sheet1',[
					$value['customer_id'],$value['telephone'],$value['order_count'],$value['last_date']
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